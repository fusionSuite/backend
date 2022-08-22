const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | integer type | bad default value', function() {

  common.defineValuetype('integer');
  common.createType();

  it('create a new property - type integer but with wrong default value', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for integer',
        internalname: 'testforinteger',
        valuetype: 'integer',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: '100',
        description: 'Test of the type integer'
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

describe('itemProperties | integer type | update to bad default value', function() {
  describe('create the property', function() {
    common.createProperty(-10);
  });

  describe('update the property', function() {
    common.updateProperty('11', 400);
  });

  describe('delete and clean', function() {
    common.deleteProperty();
  });
});
