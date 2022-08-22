const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');
const { toUnicode } = require('punycode');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('roles | custom > structure > custom > role | get role(s)', function() {

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

  it ('get myrole, return 401', function(done) {
    request
    .get('/v1/config/roles')
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

  it ('update permissions to view myrole role', function(done) {
    request
    .patch('/v1/config/roles/'+global.roleId+'/permissionstructure/'+global.permissionstructureroleId+'/custom/'+global.roleMyrolePermissionId)
    .send({
      view: true
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

  it ('get roles, return only 1', function(done) {
    request
    .get('/v1/config/roles')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.array(response.body));
      assert(is.equal(response.body.length, 1))
      assert(is.equal(response.body[0].name, 'myrole'))
      assert(is.equal(response.body[0].id, global.myroleId))
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('get a specific role (myrole)', function(done) {
    request
    .get('/v1/config/roles/'+global.myroleId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.equal('myrole', response.body.name))
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('get a specific role (admin role), return 401', function(done) {
    request
    .get('/v1/config/roles/1')
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

});
