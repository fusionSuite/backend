const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/items endpoint
*/

describe('roles | custom > data | delete role', function() {
  it('soft delete the user', function(done) {
    request
    .delete('/v1/items/' + global.user1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect('Content-Type', /json/)
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('permanently delete the user', function(done) {
    request
    .delete('/v1/items/' + global.user1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect('Content-Type', /json/)
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('soft delete the role', function(done) {
    request
    .delete('/v1/config/roles/' + global.roleId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect('Content-Type', /json/)
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('permanently delete the role', function(done) {
    request
    .delete('/v1/config/roles/' + global.roleId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
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
