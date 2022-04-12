const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | decimal type | bad default value', function() {

  common.defineValuetype('decimal');
  common.createType();

  it('create a new property - type decimal but with wrong default value (integer)', function(done) {
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
        description: 'Test of the type decimal'
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

  it('create a new property - type decimal but with wrong default value (string)', function(done) {
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
        default: '3.1416',
        description: 'Test of the type decimal'
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

describe('itemProperties | decimal type | update to bad default value', function() {
  describe('create the property', function() {
    common.createProperty(3.1416);
  });

  describe('update the property', function() {
    common.updateProperty(3, 400);
  });

  describe('delete and clean', function() {
    common.deleteProperty();
  });
});
