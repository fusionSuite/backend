const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('logAudit | log connections', function () {
  it('truncate audits database table', function (done) {
    requestDB
      .get('/truncate/audits')
      .expect(200)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('do a successfully login', function (done) {
    request
      .post('/v1/token')
      .send({ login: 'admin', password: 'admin' })
      .set('Accept', 'application/json')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 3));

        assert(validator.isJWT(response.body.token));
        assert(validator.matches(response.body.refreshtoken, /^\w+$/));

        assert(is.integer(response.body.expires));
        assert(validator.matches('' + response.body.expires, /^\d{10}$/));
        global.token = response.body.token;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('do a failed login with unknow user', function (done) {
    request
      .post('/v1/token')
      .send({ login: 'adminfjewrighrw', password: 'admin' })
      .set('Accept', 'application/json')
      .expect(401)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('do a query with malformed token', function (done) {
    request
      .get('/v1/config/types')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer eyJ0eXAiOiJKV1QLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM2MTcyMzQsImV4cCI6MTY2MzYxODQzNCwianRpIjoiIiwic3ViIjoiIiwic2NvcGUiOltdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOiJTdGV2ZSIsImxhc3RuYW1lIjoiUm9nZXJzIiwiYXBpdmVyc2lvbiI6InYxIiwib3JnYW5pemF0aW9uX2lkIjoxLCJzdWJfb3JnYW5pemF0aW9uIjp0cnVlfQ.NuHFCoaVVLPWq_Zydq6XdqPVO9VkF0mmtRwLhRNArGk')
      .expect(401)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('do a query with wrong signature in token', function (done) {
    request
      .get('/v1/config/types')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjM2MTcyMzQsImV4cCI6MTY2MzYxODQzNCwianRpIjoiIiwic3ViIjoiIiwic2NvcGUiOltdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOiJTdGV2ZSIsImxhc3RuYW1lIjoiUm9nZXJzIiwiYXBpdmVyc2lvbiI6InYxIiwib3JnYW5pemF0aW9uX2lkIjoxLCJzdWJfb3JnYW5pemF0aW9uIjp0cnVlfQ.NuHFCoaVVLPWq_Zydq6XdqPVO9VkF0mmtRwLhRNArgk')
      .expect(401)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('verify audits added', function (done) {
    request
      .get('/v1/log/audits')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.array(response.body));
        assert(is.equal(4, response.body.length));

        const successLogin = response.body[0];
        assert(is.equal(200, successLogin.httpcode));
        assert(is.equal('', successLogin.message));
        assert(validator.isISO8601(successLogin.created_at), 'the log created_at must be a valid ISO8601 date');
        assert(is.object(successLogin.user));
        assert(is.equal(4, Object.keys(successLogin.user).length), 'the user data must have 4 attributes');
        assert(is.equal(2, successLogin.user.id));
        assert(is.equal('admin', successLogin.user.name));
        assert(is.equal('Steve', successLogin.user.first_name));
        assert(is.equal('Rogers', successLogin.user.last_name));

        const failLogin = response.body[1];
        assert(is.equal(401, failLogin.httpcode));
        assert(is.equal('fail, login: adminfjewrighrw', failLogin.message));

        const malformedToken = response.body[2];
        assert(is.equal(401, malformedToken.httpcode));
        assert(is.equal('Unexpected control character found', malformedToken.message));

        const badSignatureToken = response.body[3];
        assert(is.equal(401, badSignatureToken.httpcode));
        assert(is.equal('Signature verification failed', badSignatureToken.message));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
