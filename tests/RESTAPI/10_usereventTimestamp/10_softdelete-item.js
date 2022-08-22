const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('usereventTimestamp | soft delete item', function() {

  it ('soft delete the item with user2', function(done) {
    request
    .delete('/v1/items/'+global.itemId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser2)
    .expect(200)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('check the item fields', function(done) {
    request
    .get('/v1/items/'+global.itemId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.equal('test patch', response.body.name));
      assert(validator.isISO8601(response.body.created_at, "created_at must be filled with creation date"));
      assert(validator.isISO8601(response.body.updated_at, "updated_at must be filled with update date"));
      assert(validator.isISO8601(response.body.deleted_at, "deleted_at must be filled with delete date"));
      assert(is.not.equal(response.body.deleted_at, response.body.updated_at, "deleted_at and updated_at must be different"));
      assert(is.not.equal(response.body.deleted_at, response.body.created_at, "deleted_at and created_at must be different"));

      assert(is.equal(2, response.body.created_by.id, 'created_by must be filled with admin user id'));
      assert(is.equal(global.user1, response.body.updated_by.id, 'updated_by must be filled with user1 id'));
      assert(is.equal(global.user2, response.body.deleted_by.id, 'deleted_by must be filled with user2 id'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
