const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | decimal type | null not allowed | create & update items', function () {
  describe('prepare', function () {
    it('define the type decimal', function (done) {
      common.defineValuetype(done, 'decimal');
    });

    it('create a new type decimal', function (done) {
      common.createType(done, 'decimal');
    });

    it('create the property', function (done) {
      common.createProperty(done, 3.1416, false);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 3.1416);
    });

    it('Attach a property to the type decimal', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('Tests with null value', function () {
    it('item, create: null value => error', function (done) {
      common.createItemWithError(done, null, 'The Value can\'t be null');
    });

    // create item and try to update it with null value
    it('item, create', function (done) {
      commonCreateItem.createItem(done, true, 10.11);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDecimal(done, 10.11);
    });

    it('property, create: canbenull: false, null in default value => error', function (done) {
      request
        .post('/v1/config/properties')
        .send(
          {
            name: 'Test for decimal',
            internalname: 'testfordecimal',
            valuetype: 'decimal',
            regexformat: '',
            listvalues: [],
            unit: '',
            default: null,
            description: 'Test of the type decimal',
            canbenull: false,
          })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(400)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.propertyCount(response.body, 2));
          assert(validator.equals(response.body.status, 'error'));
          assert(validator.equals(response.body.message, 'The Default can\'t be null'));
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test decimal', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test decimal', function (done) {
      common.deleteType(done);
    });

    it('Soft delete the property', function (done) {
      common.deleteProperty(done);
    });

    it('Hard delete the property', function (done) {
      common.deleteProperty(done);
    });
  });
});
