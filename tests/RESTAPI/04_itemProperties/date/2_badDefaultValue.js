const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

describe('itemProperties | date type | bad default value', function () {
  describe('prepare', function () {
    it('define the type date', function (done) {
      common.defineValuetype(done, 'date');
    });

    it('create a new type date', function (done) {
      common.createType(done, 'date');
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
              name: 'Test for date',
              internalname: 'testfordate',
              valuetype: 'date',
              regexformat: '',
              listvalues: [],
              unit: '',
              default: '06-05-2022',
              description: 'Test of the type date',
            })
          .set('Accept', 'application/json')
          .set('Authorization', 'Bearer ' + global.token)
          .expect(400)
          .expect('Content-Type', /json/)
          .expect(function (response) {
            assert(is.propertyCount(response.body, 2));
            assert(validator.equals(response.body.status, 'error'));
            assert(validator.equals(response.body.message, 'The Default is not valid date'));
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

  describe('properties, update: bad default value', function () {
    it('create the property', function (done) {
      common.createProperty(done, '2022-04-25');
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, '2022-04-25');
    });

    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessage }) => {
      it('update the property ' + description + ' => error', function (done) {
        common.updateProperty(done, value, 400);
      });
    });
  });

  describe('clean', function () {
    it('Soft delete the type: test date', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test date', function (done) {
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
