const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

describe('itemProperties | decimal type | bad default value', function () {
  describe('prepare', function () {
    it('define the type decimal', function (done) {
      common.defineValuetype(done, 'decimal');
    });

    it('create a new type decimal', function (done) {
      common.createType(done, 'decimal');
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
              name: 'Test for decimal',
              internalname: 'testfordecimal',
              valuetype: 'decimal',
              regexformat: '',
              listvalues: [],
              unit: '',
              default: 10,
              description: 'Test of the type decimal',
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

  describe('property, udpate: bad default value', function () {
    it('create the property', function (done) {
      common.createProperty(done, 3.1416);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 3.1416);
    });

    describe('property, update: wrong default value (integer) => error', function () {
      // eslint-disable-next-line mocha/no-setup-in-describe
      global.dataProvider.forEach(({ description, value, errorMessage }) => {
        it('update the property ' + description + ' => error', function (done) {
          common.updateProperty(done, value, 400);
        });
      });
    });
  });

  describe('clean', function () {
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
