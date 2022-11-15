const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | integer type | null not allowed | create & update items', function () {
  describe('prepare', function () {
    it('define the type integer', function (done) {
      common.defineValuetype(done, 'integer');
    });

    it('create a new type integer', function (done) {
      common.createType(done, 'integer');
    });

    it('create the property', function (done) {
      common.createProperty(done, -10, false);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, -10);
    });

    it('Attach a property to the type integer', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: null value => error', function () {
    it('create item => error', function (done) {
      common.createItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('item, update: null value => error', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 50);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkInteger(done, 50);
    });

    it('update item', function (done) {
      common.updateItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('property, create: null by default => error', function () {
    it('create property', function (done) {
      request
        .post('/v1/config/properties')
        .send(
          {
            name: 'Test for integer',
            internalname: 'testforinteger',
            valuetype: 'integer',
            regexformat: '',
            listvalues: [],
            unit: '',
            default: null,
            description: 'Test of the type integer',
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

    it('Soft delete the type: test integer', function (done) {
      common.deleteType(done);
    });
    it('Hard delete the type: test integer', function (done) {
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
