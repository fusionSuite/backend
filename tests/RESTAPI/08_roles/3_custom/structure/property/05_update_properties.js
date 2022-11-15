const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('roles | custom > structure > property | update property(ies)', function () {
  it('update a property, return 401', function (done) {
    request
      .patch('/v1/config/properties/' + global.mypropertyId)
      .send({
        name: 'my prop test permissions updated',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(401)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'No permission on this config/property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('update permissions to update properties', function (done) {
    request
      .patch('/v1/config/roles/' + global.roleId + '/permissionstructure/' + global.permissionstructureconfigpropertyId)
      .send({
        permission: 'none',
        update: 'grant',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('update a property', function (done) {
    request
      .patch('/v1/config/properties/' + global.mypropertyId)
      .send({
        name: 'my prop test permissions updated2',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get properties, return 401 (no view right)', function (done) {
    request
      .get('/v1/config/properties')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(401)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('update permissions to view all properties', function (done) {
    request
      .patch('/v1/config/roles/' + global.roleId + '/permissionstructure/' + global.permissionstructureconfigpropertyId)
      .send({
        view: 'grant',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get the property updated', function (done) {
    request
      .get('/v1/config/properties/' + global.mypropertyId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal('myproptestpermissions', response.body.internalname));
        assert(is.equal('my prop test permissions updated2', response.body.name));
        assert(is.null(response.body.deleted_at));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
