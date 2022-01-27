const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
 * /v1/token endpoint
 */
describe('Endpoint /v1/token', function() {
  it('respond with json containing the JWT token with login & password', function(done) {
    request
    .post('/v1/token')
    .send({login: 'admin', password: 'admin'})
    .set('Accept', 'application/json')
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 3));

      assert(validator.isJWT(response.body.token));
      assert(validator.matches(response.body.refreshtoken, /^\w+$/));

      assert(is.integer(response.body.expires));
      assert(validator.matches('' + response.body.expires, /^\d{10}$/));
      global.token = response.body.token;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
