const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('organizations | items | create users', function() {

  it ('create a user on the second level of oganization', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'user1',
      type_id: 2,
      organization_id: global.subOrg1
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(is.integer(response.body.id));
      assert(is.integer(response.body.id_bytype));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.user1 = response.body.id;

      // Test get it
      request
      .get('/v1/items/'+global.user1)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.not.empty(response.body));
        assert(is.equal('user1', response.body.name));
        assert(is.equal(global.subOrg1, response.body.organization.id));
      })
      .end(function(err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
      });
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('attach user1 to the admin role', function(done) {
    request
    .post('/v1/config/roles/1/user/'+global.user1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('create a user on the third level of oganization', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'user2',
      type_id: 2,
      organization_id: global.subOrg2
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(is.integer(response.body.id));
      assert(is.integer(response.body.id_bytype));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.user2 = response.body.id;

      // Test get it
      request
      .get('/v1/items/'+global.user2)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.not.empty(response.body));
        assert(is.equal('user2', response.body.name));
        assert(is.equal(global.subOrg2, response.body.organization.id));
      })
      .end(function(err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
      });
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('attach user2 to the admin role', function(done) {
    request
    .post('/v1/config/roles/1/user/'+global.user2)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

});
