const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('organizations | types | get types with admin account', function() {

  it ('get all types with admin user', function(done) {
    request
    .get('/v1/config/types')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'response body must not be empty');
      assert(is.array(response.body), 'response body must be an array');
      assert(is.propertyCount(response.body, (typesCount + 4)), 'not have right numbr of types');
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('get type1', function(done) {
    request
    .get('/v1/config/types/'+global.mytype1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'response body must not be empty');
      assert(is.object(response.body), 'response body must be an array');
      assert(is.number(response.body.id), 'response body must have an id');
      assert(is.equal(response.body.id, global.mytype1), 'response id is wrong');
      assert(is.equal(response.body.name, 'mytype1'), 'response name is wrong');
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('get typeSub1', function(done) {
    request
    .get('/v1/config/types/'+global.mytypeSub1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'response body must not be empty');
      assert(is.object(response.body), 'response body must be an array');
      assert(is.number(response.body.id), 'response body must have an id');
      assert(is.equal(response.body.id, global.mytypeSub1), 'response id is wrong');
      assert(is.equal(response.body.name, 'mytypeSub1'), 'response name is wrong');
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('get type2', function(done) {
    request
    .get('/v1/config/types/'+global.mytype2)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'response body must not be empty');
      assert(is.object(response.body), 'response body must be an array');
      assert(is.number(response.body.id), 'response body must have an id');
      assert(is.equal(response.body.id, global.mytype2), 'response id is wrong');
      assert(is.equal(response.body.name, 'mytype2'), 'response name is wrong');
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('get type3', function(done) {
    request
    .get('/v1/config/types/'+global.mytype3)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'response body must not be empty');
      assert(is.object(response.body), 'response body must be an array');
      assert(is.number(response.body.id), 'response body must have an id');
      assert(is.equal(response.body.id, global.mytype3), 'response id is wrong');
      assert(is.equal(response.body.name, 'mytype3'), 'response name is wrong');
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

});
