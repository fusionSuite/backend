const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
 * /v1/status endpoint
 */
describe('Endpoint /v1/status', function() {
  it('respond with json containing the status of the backend', function(done) {
    request
    .get('/v1/status')
    .set('Accept', 'application/json')
    .expect('Content-Type', /json/)
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
