const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | typelinks type | bad default value', function() {

  common.defineValuetype('typelinks');
  common.createType();

  it('create a new property - type typelinks but with wrong default value', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for typelinks',
        internalname: 'testfortypelinks',
        valuetype: 'typelinks',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: 54674,
        description: 'Test of the type typelinks'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default is not valid type'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new property - type typelinks but not in array', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for typelinks',
        internalname: 'testfortypelinks',
        valuetype: 'typelinks',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: 1,
        description: 'Test of the type typelinks'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default is not valid type'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  common.deleteType();
});
