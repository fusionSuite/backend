const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('organizations | items | get items with admin', function() {

  it ('get all items in top level of oganization', function(done) {
    request
    .get('/v1/items/type/'+global.typeId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'response body must not be empty');
      assert(is.array(response.body), 'response body must be an array');
      assert(is.propertyCount(response.body, 4), 'must have the 4 items');

    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
