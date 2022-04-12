const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | propertylink type | bad default value', function() {

  common.defineValuetype('propertylink');
  common.createType();

  it('create a new type propertylink but with wrong default value', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for propertylink',
        internalname: 'testforpropertylink',
        valuetype: 'propertylink',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: "test1",
        description: 'Test of the type propertylink'
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

  it('create a new type propertylink but with id not exists', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for propertylink',
        internalname: 'testforpropertylink',
        valuetype: 'propertylink',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: 548774,
        description: 'Test of the type propertylink'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default property id does not exist'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new type propertylink but with negative integer', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for propertylink',
        internalname: 'testforpropertylink',
        valuetype: 'propertylink',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: -40,
        description: 'Test of the type propertylink'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default is not valid format'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new type propertylink but with 0', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for propertylink',
        internalname: 'testforpropertylink',
        valuetype: 'propertylink',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: 0,
        description: 'Test of the type propertylink'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default property id does not exist'));
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
