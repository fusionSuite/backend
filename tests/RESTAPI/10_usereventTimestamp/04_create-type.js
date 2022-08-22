const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('usereventTimestamp | create type', function() {

  it ('create a type with admin user', function(done) {
    request
    .post('/v1/config/types')
    .send({
      name: 'type test userevent timestamp'
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.typeId = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('check the type fields', function(done) {
    request
    .get('/v1/config/types/'+global.typeId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.equal('type test userevent timestamp', response.body.name));
      assert(validator.isISO8601(response.body.created_at, "created_at must be filled with creation date"));
      assert(validator.isISO8601(response.body.updated_at, "updated_at must be filled with creation date"));
      assert(is.null(response.body.deleted_at, 'deleted_at must be null'));
      assert(is.equal(2, response.body.created_by.id, 'created_by must be filled with admin user id'));
      assert(is.null(response.body.updated_by, 'updated_by must be null'));
      assert(is.null(response.body.deleted_by, 'deleted_by must be null'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
