const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

describe('itemProperties: itemlinks type - bad default value type', function () {
  describe('prepare', function () {
    it('define the type itemlinks', function (done) {
      common.defineValuetype(done, 'itemlinks');
    });

    it('create a new type itemlinks', function (done) {
      common.createType(done, 'itemlinks');
    });
  });

  describe('property, create: wrong default values => error', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessageDefault, errorMessage }) => {
      it('create a property ' + description + ' => error', function (done) {
        request
          .post('/v1/config/properties')
          .send(
            {
              name: 'Test for itemlinks',
              internalname: 'testforitemlinks',
              valuetype: 'itemlinks',
              regexformat: '',
              listvalues: [],
              unit: '',
              default: value,
              description: 'Test of the type itemlinks',
            })
          .set('Accept', 'application/json')
          .set('Authorization', 'Bearer ' + global.token)
          .expect(400)
          .expect('Content-Type', /json/)
          .expect(function (response) {
            assert(is.propertyCount(response.body, 2));
            assert(validator.equals(response.body.status, 'error'));
            assert(validator.equals(response.body.message, errorMessageDefault));
          })
          .end(function (err, response) {
            if (err) {
              return done(err + ' | Response: ' + response.text);
            }
            return done();
          });
      });
    });
  });

  describe('property, update: bad default values => error', function () {
    it('create the property', function (done) {
      common.createProperty(done, [global.itemId1], true, [3]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, [global.itemId1], [3]);
    });

    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessageDefault, errorMessage }) => {
      it('update the property ' + description + ' => error', function (done) {
        common.updateProperty(done, value, 400);
      });
    });
  });

  describe('clean', function () {
    it('Soft delete the type: test itemlinks', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test itemlinks', function (done) {
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
