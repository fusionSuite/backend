const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('organizations | properties | generate token for users', function () {
  it('get the token for user user1', function (done) {
    request
      .post('/v1/token')
      .send({ login: 'user1', password: 'test' })
      .set('Accept', 'application/json')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 3));

        assert(validator.isJWT(response.body.token));
        assert(validator.matches(response.body.refreshtoken, /^\w+$/));

        assert(is.integer(response.body.expires));
        assert(validator.matches('' + response.body.expires, /^\d{10}$/));
        global.tokenUser1 = response.body.token;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get the token for user user2', function (done) {
    request
      .post('/v1/token')
      .send({ login: 'user2', password: 'test' })
      .set('Accept', 'application/json')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 3));

        assert(validator.isJWT(response.body.token));
        assert(validator.matches(response.body.refreshtoken, /^\w+$/));

        assert(is.integer(response.body.expires));
        assert(validator.matches('' + response.body.expires, /^\d{10}$/));
        global.tokenUser2 = response.body.token;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
