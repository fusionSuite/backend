const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('roles | custom > structure > custom > property | create property(s)', function () {
  it('create a property, return 401', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'my prop test permissions',
        internalname: 'myproptestpermissions',
        valuetype: 'string',
        unit: '',
        default: '',
        listvalues: [],
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

  it('update permissions to create properties', function (done) {
    request
      .patch('/v1/config/roles/' + global.roleId + '/permissionstructure/' + global.permissionstructureconfigpropertyId)
      .send({
        create: 'grant',
        view: 'custom',
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

  it('create a property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'my prop test permissions',
        internalname: 'myproptestpermissions',
        valuetype: 'string',
        unit: '',
        default: '',
        listvalues: [],
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.mypropertyId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get the role and check if structure custom permissions ok', function (done) {
    request
      .get('/v1/config/roles/' + global.roleId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('custom', response.body.permissionstructure));
        assert(is.equal('grant', response.body.permissiondata));
        global.propertyMypropertyPermissionId = 0;
        response.body.permissionstructures.forEach(element => {
          if (element.endpoint === 'config/property') {
            element.customs.forEach(propPerm => {
              if (propPerm.endpoint_id === global.mypropertyId) {
                global.propertyMypropertyPermissionId = propPerm.id;
              }
            });
          }
        });
        assert(is.not.equal(0, global.propertyMypropertyPermissionId));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  // it('get types, return 401 (no view right)', function (done) {
  //   request
  //   .get('/v1/config/types')
  //   .set('Accept', 'application/json')
  //   .set('Authorization', 'Bearer ' + global.tokenUser1)
  //   .expect(401)
  //   .expect('Content-Type', /json/)
  //   .end(function (err, response) {
  //     if (err) {
  //       return done(err + ' | Response: ' + response.text);
  //     }
  //     return done();
  //   });
  // });

  // it('update permissions to view all types', function (done) {
  //   request
  //   .patch('/v1/config/roles/' + global.roleId + '/permissionstructure/' + global.permissionstructureconfigtypeId)
  //   .send({
  //     view: true
  //   })
  //   .set('Accept', 'application/json')
  //   .set('Authorization', 'Bearer ' + global.token)
  //   .expect(200)
  //   .expect('Content-Type', /json/)
  //   .end(function (err, response) {
  //     if (err) {
  //       return done(err + ' | Response: ' + response.text);
  //     }
  //     return done();
  //   });
  // });

  // it('get the type created', function (done) {
  //   request
  //   .get('/v1/config/types/' + global.mytypeId)
  //   .set('Accept', 'application/json')
  //   .set('Authorization', 'Bearer ' + global.tokenUser1)
  //   .expect(200)
  //   .expect('Content-Type', /json/)
  //   .expect(function (response) {
  //     assert(is.equal('mytype', response.body.internalname))
  //   })
  //   .end(function (err, response) {
  //     if (err) {
  //       return done(err + ' | Response: ' + response.text);
  //     }
  //     return done();
  //   });
  // });
});
