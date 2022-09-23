const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | passwordhash type | bad default value', function () {
  describe('prepare', function () {
    it('define the type passwordhash', function (done) {
      common.defineValuetype(done, 'passwordhash');
    });

    it('create a new type passwordhash', function (done) {
      common.createType(done, 'passwordhash');
    });
  });

  it('create a new property - type passwordhash but with wrong default value (boolean)', function (done) {
    request
      .post('/v1/config/properties')
      .send(
        {
          name: 'Test for passwordhash',
          internalname: 'testforpasswordhash',
          valuetype: 'passwordhash',
          regexformat: '',
          listvalues: [],
          unit: '',
          default: true,
          description: 'Test of the type passwordhash',
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

  it('create a new property - type passwordhash but with wrong default value (integer)', function (done) {
    request
      .post('/v1/config/properties')
      .send(
        {
          name: 'Test for passwordhash',
          internalname: 'testforpasswordhash',
          valuetype: 'passwordhash',
          regexformat: '',
          listvalues: [],
          unit: '',
          default: 4,
          description: 'Test of the type passwordhash',
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

  describe('clean', function () {
    it('Soft delete the type: test number', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test number', function (done) {
      common.deleteType(done);
    });
  });
});
