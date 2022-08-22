const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | string type | bad default value', function() {

  common.defineValuetype('string');
  common.createType();

  it('create a new property - type string but with wrong default value', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for string',
        internalname: 'testforstring',
        valuetype: 'string',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: true,
        description: 'Test of the type string'
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

  it('create a new property - type string but with too long default value', function(done) {
    request
    .post('/v1/config/properties')
    .send(
      {
        name: 'Test for string',
        internalname: 'testforstring',
        valuetype: 'string',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: 'Lorem ipsum dolor sit amet. Est porro eius sed dolorum consequatur et ducimus distinctio qui eius porro. Cum facilis quaerat ut excepturi animi qui vero voluptatum et cupiditate fuga et autem neque qui consectetur vitae qui delectus neque? Aut soluta ratione ad cupiditate maiores et modi rerum ad dignissimos nisi aut debi',
        description: 'Test of the type string'
      })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Default property has too many characters'));
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
