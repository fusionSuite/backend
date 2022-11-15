const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | date type | null not allowed | create & update items', function () {
  describe('prepare', function () {
    it('define the type date', function (done) {
      common.defineValuetype(done, 'date');
    });

    it('create a new type date', function (done) {
      common.createType(done, 'date');
    });

    it('create the property', function (done) {
      common.createProperty(done, '2022-04-25', false);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, '2022-04-25');
    });

    it('Attach a property to the type date', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: null value', function () {
    it('try create a new item (try to define null value) => error', function (done) {
      common.createItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('item, update: null value', function () {
    // create item and try to update it with null value
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, '2022-05-06');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDate(done, '2022-05-06');
    });

    it('try update the item (use null value) => error', function (done) {
      common.updateItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('property, create: default null', function () {
    it('create a new property, canbenull: false, define null by default value => error', function (done) {
      request
        .post('/v1/config/properties')
        .send(
          {
            name: 'Test for date',
            internalname: 'testfordate',
            valuetype: 'date',
            regexformat: '',
            listvalues: [],
            unit: '',
            default: null,
            description: 'Test of the type date',
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

    it('Soft delete the type', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type', function (done) {
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
