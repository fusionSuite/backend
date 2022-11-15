const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('roles | custom > structure > custom > type | create type(s)', function () {
  it('create a type, return 401', function (done) {
    request
      .post('/v1/config/types')
      .send({
        name: 'mytype',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(401)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'No permission on this config/type'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('update permissions to create types', function (done) {
    request
      .patch('/v1/config/roles/' + global.roleId + '/permissionstructure/' + global.permissionstructureconfigtypeId)
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

  it('create a type', function (done) {
    request
      .post('/v1/config/types')
      .send({
        name: 'mytype',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.mytypeId = response.body.id;
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
        global.typeMytypePermissionId = 0;
        response.body.permissionstructures.forEach(element => {
          if (element.endpoint === 'config/type') {
            element.customs.forEach(typePerm => {
              if (typePerm.endpoint_id === global.mytypeId) {
                global.typeMytypePermissionId = typePerm.id;
              }
            });
          }
        });
        assert(is.not.equal(0, global.typeMytypePermissionId));
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
