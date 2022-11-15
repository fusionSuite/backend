const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('roles | custom > structure > type | create role', function () {
  it('create a new role', function (done) {
    request
      .post('/v1/config/roles')
      .send({
        name: 'role1',
        permissionstructure: 'custom',
        permissiondata: 'grant',
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

  it('get the role and check if custom permissions ok', function (done) {
    request
      .get('/v1/config/roles/' + global.roleId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('grant', response.body.permissiondata));
        assert(is.equal('custom', response.body.permissionstructure));
        global.permissionstructurelaptopId = 0;
        response.body.permissionstructures.forEach(element => {
          if (element.endpoint === 'config/type') {
            global.permissionstructureconfigtypeId = element.id;
          }
        });
        assert(is.not.equal(0, global.permissionstructureconfigtypeId, 'config/type endpoint item not found'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
