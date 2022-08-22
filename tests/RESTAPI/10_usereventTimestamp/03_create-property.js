const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('usereventTimestamp | create property', function() {

  it ('create a property with admin user', function(done) {
    request
    .post('/v1/config/properties')
    .send({
      name: 'property test userevent timestamp',
      valuetype: 'string',
      listvalues: [],
      default: ""
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.propertyId = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('check the property fields', function(done) {
    request
    .get('/v1/config/properties/'+global.propertyId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.equal('property test userevent timestamp', response.body.name));
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
