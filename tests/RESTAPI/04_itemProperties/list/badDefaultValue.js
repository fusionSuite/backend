const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | list type | bad default value', function() {

  common.defineValuetype('list');
  common.createType();

  it('create a new property - type list but with wrong default value', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for list',
        internalname: 'testforlist',
        valuetype: 'list',
        regexformat: '',
        listvalues: ["list1","list2"],
        unit: '',
        default: 432,
        description: 'Test of the type list'
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

  it('create a new property - type list but with wrong string (not in listvalues', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for list',
        internalname: 'testforlist',
        valuetype: 'list',
        regexformat: '',
        listvalues: ["list1","list2"],
        unit: '',
        default: 'list3',
        description: 'Test of the type list'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default property does not exist in listvalues'));
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
