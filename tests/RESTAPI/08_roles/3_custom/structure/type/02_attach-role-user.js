const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('roles | custom > structure > type | attach the role to user', function() {

  it ('create a user (user1), will be used to test the permissions', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'user1',
      type_id: 2
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
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('attach user to the role', function(done) {
    request
    .post('/v1/config/roles/'+global.roleId+'/user/'+global.user1)
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

  it('get the role and check if user associated', function(done) {
    request
    .get('/v1/config/roles/'+global.roleId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body.users));
      assert(is.equal('user1', response.body.users[0].name));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  })

  it ('get the token for user user1', function(done) {
    request
    .post('/v1/token')
    .send({login: 'user1', password: 'test'})
    .set('Accept', 'application/json')
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 3));

      assert(validator.isJWT(response.body.token));
      assert(validator.matches(response.body.refreshtoken, /^\w+$/));

      assert(is.integer(response.body.expires));
      assert(validator.matches('' + response.body.expires, /^\d{10}$/));
      global.tokenUser1 = response.body.token;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
