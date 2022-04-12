const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | date type | bad default value', function() {

  common.defineValuetype('date');
  common.createType();

  it('create a new property | type date | with wrong default value (inversed date)', function(done) {
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
        description: 'Test of the type date'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default is not valid date'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new property - type date but with wrong default value (number instead date string)', function(done) {
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
        default: 2022,
        description: 'Test of the type date'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default is not valid type, The Default is not valid date'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new property - type date but with wrong default value (date with wrong month)', function(done) {
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
        default: '2022-21-05',
        description: 'Test of the type date'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default is not valid date'));
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

describe('itemProperties | date type | update to bad default value', function() {
  describe('create the property', function() {
    common.createProperty('2022-04-25');
  });

  describe('update the property', function() {
    common.updateProperty(2022, 400);
  });

  describe('delete and clean', function() {
    common.deleteProperty();
  });
});
