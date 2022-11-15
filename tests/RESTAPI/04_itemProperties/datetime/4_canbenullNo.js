const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | datetime type | null not allowed | create & update items', function () {
  describe('prepare', function () {
    it('define the type datetime', function (done) {
      common.defineValuetype(done, 'datetime');
    });

    it('create a new type datetime', function (done) {
      common.createType(done, 'datetime');
    });

    it('create the property', function (done) {
      common.createProperty(done, '2022-04-25 08:12:45', false);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, '2022-04-25 08:12:45');
    });

    it('Attach a property to the type datetime', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: null value => error', function () {
    it('item, create: null value => error', function (done) {
      common.createItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('item, update: null value => error', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, '2022-04-25 08:12:45');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDatetime(done, '2022-04-25 08:12:45');
    });

    it('try create a new item but return error (use null value)', function (done) {
      common.createItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('property, create: null => error', function () {
    it('property, create: canbenull: false, null by default value => error', function (done) {
      request
        .post('/v1/config/properties')
        .send(
          {
            name: 'Test for datetime',
            internalname: 'testfordatetime',
            valuetype: 'datetime',
            regexformat: '',
            listvalues: [],
            unit: '',
            default: null,
            description: 'Test of the type datetime',
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

    it('Soft delete the type: test datetime', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test datetime', function (done) {
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
