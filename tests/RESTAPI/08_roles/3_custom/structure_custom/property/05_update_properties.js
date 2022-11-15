const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('roles | custom > structure > custom > property | update property(s)', function () {
  it('update a property, return 401', function (done) {
    request
      .patch('/v1/config/properties/' + global.mypropertyId)
      .send({
        name: 'mypropertyupdated',
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

  it('update permissions to update myproperty property', function (done) {
    request
      .patch('/v1/config/roles/' + global.roleId + '/permissionstructure/' + global.permissionstructureconfigpropertyId + '/custom/' + global.propertyMypropertyPermissionId)
      .send({
        view: false,
        update: true,
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

  it('update a property (error because custom allowed but second level no)', function (done) {
    request
      .patch('/v1/config/properties/' + global.mypropertyId)
      .send({
        name: 'mypropertyupdated2',
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

  it('update permissions to custom update properties', function (done) {
    request
      .patch('/v1/config/roles/' + global.roleId + '/permissionstructure/' + global.permissionstructureconfigpropertyId)
      .send({
        update: 'custom',
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
        name: 'mypropertyupdated2',
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

  it('update permissions to view myproperty property', function (done) {
    request
      .patch('/v1/config/roles/' + global.roleId + '/permissionstructure/' + global.permissionstructureconfigpropertyId + '/custom/' + global.propertyMypropertyPermissionId)
      .send({
        view: true,
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
        assert(is.equal('mypropertyupdated2', response.body.name));
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
