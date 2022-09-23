const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('password user login | login', function () {
  it('login successfull', function (done) {
    request
      .post('/v1/token')
      .send({ login: 'user1', password: 'test my password' })
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
        global.refreshTokenUser1 = response.body.refreshtoken;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try login but with bad password', function (done) {
    request
      .post('/v1/token')
      .send({ login: 'user1', password: 'test my passworD' })
      .set('Accept', 'application/json')
      .expect(401)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Error when authentication, login or password not right'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
