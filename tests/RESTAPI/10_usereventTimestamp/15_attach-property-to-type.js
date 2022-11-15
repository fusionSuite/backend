const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('usereventTimestamp | attach property to type', function () {
  it('wait 1 second to be sure modification update the field updated_at', function (done) {
    setTimeout(function () {
      return done();
    }, 1000);
  });

  it('attach property to type with user2', function (done) {
    request
      .post('/v1/config/types/' + global.typeId + '/property/2')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser2)
      .expect(200)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check the type fields', function (done) {
    request
      .get('/v1/config/types/' + global.typeId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('test patch', response.body.name));
        assert(validator.isISO8601(response.body.created_at, 'created_at must be filled with creation date'));
        assert(validator.isISO8601(response.body.updated_at, 'updated_at must be filled with update date'));
        assert(is.null(response.body.deleted_at, 'deleted_at must be null'));
        assert(is.not.equal(response.body.updated_at, global.propertyUpdatedAt, 'updated_at must be updated since restore type'));

        assert(is.equal(2, response.body.created_by.id, 'created_by must be filled with admin user id'));
        assert(is.equal(global.user2, response.body.updated_by.id, 'updated_by must be filled with user1 id'));
        assert(is.null(response.body.deleted_by, 'deleted_by must be null'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
