const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

describe('itemProperties | itemlink type | null not allowed | create & update items', function () {
  describe('prepare', function () {
    it('define the type itemlink', function (done) {
      common.defineValuetype(done, 'itemlink');
    });

    it('create a new type itemlink', function (done) {
      common.createType(done, 'itemlink');
    });

    it('create a property', function (done) {
      common.createProperty(done, global.itemId1, false);
    });

    it('Attach a property to the type itemlink', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: null value => error', function () {
    it('create item => error', function (done) {
      common.createItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('item,create: null value => error', function () {
    it('try create a new item but return error (use null value)', function (done) {
      common.createItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('property, create: default null => error', function () {
    it('property, create:  canbenull: false, null by default value', function (done) {
      request
        .post('/v1/config/properties')
        .send(
          {
            name: 'Test for itemlink',
            internalname: 'testforitemlink',
            valuetype: 'itemlink',
            regexformat: '',
            listvalues: [],
            unit: '',
            default: null,
            description: 'Test of the type itemlink',
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
    it('Soft delete the type: test itemlink', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test itemlink', function (done) {
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
