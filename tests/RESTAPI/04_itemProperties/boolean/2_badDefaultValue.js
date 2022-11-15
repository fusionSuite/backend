const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

describe('itemProperties | boolean type | bad default value', function () {
  describe('prepare', function () {
    it('define the type boolean', function (done) {
      common.defineValuetype(done, 'boolean');
    });
  });

  describe('property, create: wrong default values => error', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessage }) => {
      it('create a property ' + description, function (done) {
        request
          .post('/v1/config/properties')
          .send(
            {
              name: 'Test for boolean',
              internalname: 'testforboolean',
              valuetype: 'boolean',
              regexformat: '',
              listvalues: [],
              unit: '',
              default: 0,
              description: 'Test of the type boolean',
            })
          .set('Accept', 'application/json')
          .set('Authorization', 'Bearer ' + global.token)
          .expect(400)
          .expect('Content-Type', /json/)
          .expect(function (response) {
            assert(is.propertyCount(response.body, 2));
            assert(validator.equals(response.body.status, 'error'));
            assert(validator.equals(response.body.message, 'The Default is not valid type'));
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

  describe('property, update: default value 0', function () {
    it('create a property ', function (done) {
      common.createProperty(done, true);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, true);
    });

    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessage }) => {
      it('update the property ' + description + ' => error', function (done) {
        common.updateProperty(done, value, 400, errorMessage);
      });
    });
  });

  describe('clean', function () {
    it('Soft delete the property', function (done) {
      common.deleteProperty(done);
    });
    it('Hard delete the property', function (done) {
      common.deleteProperty(done);
    });
  });
});
