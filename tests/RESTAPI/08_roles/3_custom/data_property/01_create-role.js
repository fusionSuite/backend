const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('roles | custom > data > properties | create role', function () {
  it('create a new role', function (done) {
    request
      .post('/v1/config/roles')
      .send({
        name: 'role1',
        permissionstructure: 'grant',
        permissiondata: 'custom',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.roleId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get the role and check if data permissions ok', function (done) {
    request
      .get('/v1/config/roles/' + global.roleId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('grant', response.body.permissionstructure));
        assert(is.equal('custom', response.body.permissiondata));
        global.permissiondataId = 0;
        response.body.permissiondatas.forEach(element => {
          if (element.type.id === 3) {
            global.permissiondataId = element.id;
          }
        });
        assert(is.not.equal(0, global.permissiondataId));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('set permission to laptop to view only and activate custom of properties', function (done) {
    request
      .patch('/v1/config/roles/' + global.roleId + '/permissiondata/' + global.permissiondataId)
      .send({
        view: true,
        create: true,
        update: false,
        softdelete: false,
        delete: false,
        propertiescustom: true,
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

  it('get the role and check if data properties permissions ok', function (done) {
    request
      .get('/v1/config/roles/' + global.roleId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('grant', response.body.permissionstructure));
        assert(is.equal('custom', response.body.permissiondata));
        global.propertySerialNumberPermissionId = 0;
        global.propertyInventorynumberPermissionId = 0;
        response.body.permissiondatas.forEach(element => {
          if (element.type.id === 3) {
            element.properties.forEach(propPerm => {
              if (propPerm.property.name === 'Serial number') {
                global.propertySerialNumberPermissionId = propPerm.id;
                global.propertySerialNumberPermissionPropId = propPerm.property.id;
              }
              if (propPerm.property.name === 'Inventory number') {
                global.propertyInventorynumberPermissionId = propPerm.id;
                global.propertyInventorynumberPermissionPropId = propPerm.property.id;
              }
            });
          }
        });
        assert(is.not.equal(0, global.propertySerialNumberPermissionId));
        assert(is.not.equal(0, global.propertyInventorynumberPermissionId));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
