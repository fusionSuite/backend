const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties: datetime type - bad default value type', function() {

  common.defineValuetype('datetime');
  common.createType();

  it('create a new property | type datetime | with wrong default value', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for datetime',
        internalname: 'testfordatetime',
        valuetype: 'datetime',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: '2022-04-25 21:02',
        description: 'Test of the type datetime'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default is not valid datetime'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new property - type datetime but with wrong default value (number)', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for datetime',
        internalname: 'testfordatetime',
        valuetype: 'datetime',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: 2022,
        description: 'Test of the type datetime'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default is not valid type, The Default is not valid datetime'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new property - type datetime but with wrong default value (anormal hour)', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for datetime',
        internalname: 'testfordatetime',
        valuetype: 'datetime',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: '2022-04-25 27:02:04',
        description: 'Test of the type datetime'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default is not valid datetime'));
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

describe('itemProperties | datetime type | update to bad default value', function() {
  describe('create the property', function() {
    common.createProperty('2022-04-25 21:02:12');
  });

  describe('update the property', function() {
    common.updateProperty('2022-06-18', 400);
  });

  describe('delete and clean', function() {
    common.deleteProperty();
  });
});