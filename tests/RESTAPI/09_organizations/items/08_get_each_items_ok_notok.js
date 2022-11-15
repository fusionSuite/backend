const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('organizations | items | get each item | ok and notok', function () {
  it('get item1 with admin', function (done) {
    request
      .get('/v1/items/' + global.myitem1)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'response body must not be empty');
        assert(is.object(response.body), 'response body must be an object');
        assert(is.equal(response.body.id, global.myitem1), 'must have the id of the myitem1');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get myitem1 with user1 (not have access)', function (done) {
    request
      .get('/v1/items/' + global.myitem1)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'This item is not in your organization'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get myitem1 with user2 (not have access)', function (done) {
    request
      .get('/v1/items/' + global.myitem1)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser2)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'This item is not in your organization'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get myitem2 with admin', function (done) {
    request
      .get('/v1/items/' + global.myitem2)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'response body must not be empty');
        assert(is.object(response.body), 'response body must be an object');
        assert(is.equal(response.body.id, global.myitem2), 'must have the id of the myitem2');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get myitem2 with user1', function (done) {
    request
      .get('/v1/items/' + global.myitem2)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'response body must not be empty');
        assert(is.object(response.body), 'response body must be an object');
        assert(is.equal(response.body.id, global.myitem2), 'must have the id of the myitem2');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get myitem3 with user2 (not have access)', function (done) {
    request
      .get('/v1/items/' + global.myitem2)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser2)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'This item is not in your organization'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get myitemSub1 with user2 (ok because the item is defined as sub organization)', function (done) {
    request
      .get('/v1/items/' + global.myitemSub1)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser2)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'response body must not be empty');
        assert(is.object(response.body), 'response body must be an object');
        assert(is.equal(response.body.id, global.myitemSub1), 'must have the id of the myitemSub1');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
