const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('refresh token | usage of the refreshtoken', function () {
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
        global.refreshTokenUser1 = response.body.refreshtoken;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('wait 1 second to be sure timestamp changed', function (done) {
    setTimeout(function () {
      return done();
    }, 1000);
  });

  it('try login with refresh token and no jwt', function (done) {
    request
      .post('/v1/refreshtoken')
      .send({
        refreshtoken: global.refreshTokenUser1,
      })
      .set('Accept', 'application/json')
      .expect(400)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'The Token is required'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try login with no refresh token and jwt', function (done) {
    request
      .post('/v1/refreshtoken')
      .send({
        token: global.tokenUser1,
      })
      .set('Accept', 'application/json')
      .expect(400)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'The Refreshtoken is required'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try login with no refresh token and no jwt', function (done) {
    request
      .post('/v1/refreshtoken')
      .send({})
      .set('Accept', 'application/json')
      .expect(400)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'The Token is required, The Refreshtoken is required'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try login with wrong type of refresh token and jwt', function (done) {
    request
      .post('/v1/refreshtoken')
      .send({
        token: true,
        refreshtoken: global.refreshTokenUser1,
      })
      .set('Accept', 'application/json')
      .expect(400)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'The Token is not valid type'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try login with refresh token and JWT malformed', function (done) {
    request
      .post('/v1/refreshtoken')
      .send({
        token: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjcwMzxNjQsImV4cCI6MTY2NzAzMDE5NCwianRpIjoiIiwic3ViIjoiIiwic2NvcGUiOltdLCJ1c2VyX2lkIjoyLCJyb2xlX2lkIjoxLCJmaXJzdG5hbWUiOiJTdGV2ZSIsImxhc3RuYW1lIjoiUm9nZXJzIiwiYXBpdmVyc2lvbiI6InYxIiwib3JnYW5pemF0aW9uX2lkIjoxLCJzdWJfb3JnYW5pemF0aW9uIjp0cnVlfQ.PGyKarzzi2_O4xGXF_GC8DFqj8AlGjD_R8yAl3UnjIw',
        refreshtoken: global.refreshTokenUser1,
      })
      .set('Accept', 'application/json')
      .expect(500)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Syntax error, malformed JSON'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try login with refresh token and JWT have wrong signature', function (done) {
    request
      .post('/v1/refreshtoken')
      .send({
        token: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NjcwMzEwMTksImV4cCI6MTY2NzAzMTA0OSwianRpIjoiIiwic3ViIjoiIiwic2NvcGUiOltdLCJ1c2VyX2lkIjo1LCJyb2xlX2lkIjoxLCJmaXJzdG5hbWUiOiJ5byIsImxhc3RuYW1lIjoibG8iLCJhcGl2ZXJzaW9uIjoidjEiLCJvcmdhbml6YXRpb25faWQiOjEsInN1Yl9vcmdhbml6YXRpb24iOnRydWV9.fsLmNtavIAuYHgAxziaMCMDhoxD0DP0mpSoNitJ2ihM',
        refreshtoken: global.refreshTokenUser1,
      })
      .set('Accept', 'application/json')
      .expect(500)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Signature verification failed'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try login with wrong refresh token and right JWT', function (done) {
    request
      .post('/v1/refreshtoken')
      .send({
        token: global.tokenUser1,
        refreshtoken: 'xxxxx',
      })
      .set('Accept', 'application/json')
      .expect(401)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Error when authentication, refreshtoken not right'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try login with right refresh token and right JWT', function (done) {
    request
      .post('/v1/refreshtoken')
      .send({
        token: global.tokenUser1,
        refreshtoken: global.refreshTokenUser1,
      })
      .set('Accept', 'application/json')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 3));

        assert(validator.isJWT(response.body.token));
        assert(validator.matches(response.body.refreshtoken, /^\w+$/));

        assert(is.integer(response.body.expires));
        assert(validator.matches('' + response.body.expires, /^\d{10}$/));
        assert(is.not.equal(response.body.token, global.tokenUser1));
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
});
