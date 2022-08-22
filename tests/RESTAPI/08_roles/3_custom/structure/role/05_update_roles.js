const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('roles | custom > structure > role | update role(s)', function() {

  it ('update a role, return 401', function(done) {
    request
    .patch('/v1/config/roles/'+global.myroleId)
    .send({
      name: 'myroleupdated'
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(401)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'No permission on this config/role'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });


  it ('update permissions to update roles', function(done) {
    request
    .patch('/v1/config/roles/'+global.roleId+'/permissionstructure/'+global.permissionstructureroleId)
    .send({
      permission: 'none',
      update: 'grant'
    })
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

  it ('update a role', function(done) {
    request
    .patch('/v1/config/roles/'+global.myroleId)
    .send({
      name: 'myroleupdated2'
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(200)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('get roles, return 401 (no view right)', function(done) {
    request
    .get('/v1/config/roles')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(401)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('update permissions to view all roles', function(done) {
    request
    .patch('/v1/config/roles/'+global.roleId+'/permissionstructure/'+global.permissionstructureroleId)
    .send({
      view: 'grant'
    })
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

  it ('get the role updated', function(done) {
    request
    .get('/v1/config/roles/'+global.myroleId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.equal('myroleupdated2', response.body.name));
      assert(is.null(response.body.deleted_at));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

});
