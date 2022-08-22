const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('roles | none | check user not have access to items', function() {

  it ('get the token for user user1', function(done) {
    request
    .post('/v1/token')
    .send({login: 'user1', password: 'test'})
    .set('Accept', 'application/json')
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 3));

      assert(validator.isJWT(response.body.token));
      assert(validator.matches(response.body.refreshtoken, /^\w+$/));

      assert(is.integer(response.body.expires));
      assert(validator.matches('' + response.body.expires, /^\d{10}$/));
      global.tokenUser1 = response.body.token;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('try get laptops => error', function(done) {
    request
    .get('/v1/items/type/3')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(401)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('try create a laptop => error', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'myitem1',
      type_id: 3,
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(401)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
