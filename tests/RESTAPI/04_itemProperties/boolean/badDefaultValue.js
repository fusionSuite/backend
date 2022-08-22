const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | boolean type | bad default value', function() {

  common.defineValuetype('boolean');
  common.createType();

  it('create a new property - type boolean but with wrong default value', function(done) {
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
        description: 'Test of the type boolean'
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

describe('itemProperties | boolean type | update to bad default value', function() {
  describe('create the property', function() {
    common.createProperty(true);
  });

  describe('update the property', function() {
    common.updateProperty(0, 400);
  });

  describe('delete and clean', function() {
    common.deleteProperty();
  });
});
