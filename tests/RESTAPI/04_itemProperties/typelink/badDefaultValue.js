const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | typelink type | bad default value', function() {

  common.defineValuetype('typelink');
  common.createType();

  it('create a new property - type typelink but with wrong default value', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for typelink',
        internalname: 'testfortypelink',
        valuetype: 'typelink',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: 'test',
        description: 'Test of the type typelink'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default is not valid type, The Default is not valid format'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new property - type typelink but with id 0 for default value', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for typelink',
        internalname: 'testfortypelink',
        valuetype: 'typelink',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: 0,
        description: 'Test of the type typelink'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default type does not exist'));
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
