const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

describe('itemProperties | number type | bad default value', function () {
  describe('prepare', function () {
    it('define the type number', function (done) {
      common.defineValuetype(done, 'number');
    });

    it('create a new type number', function (done) {
      common.createType(done, 'number');
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
              name: 'Test for number',
              internalname: 'testfornumber',
              valuetype: 'number',
              regexformat: '',
              listvalues: [],
              unit: '',
              default: value,
              description: 'Test of the type number',
            })
          .set('Accept', 'application/json')
          .set('Authorization', 'Bearer ' + global.token)
          .expect(400)
          .expect('Content-Type', /json/)
          .expect(function (response) {
            assert(is.propertyCount(response.body, 2));
            assert(validator.equals(response.body.status, 'error'));
            assert(validator.equals(response.body.message, errorMessage));
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

  describe('clean', function () {
    it('Soft delete the type: test number', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test number', function (done) {
      common.deleteType(done);
    });
  });
});
