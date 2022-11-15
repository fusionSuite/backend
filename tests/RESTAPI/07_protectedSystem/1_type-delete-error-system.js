const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('protectedSystem | delete system types give an error', function () {
  it('get system types', function (done) {
    request
      .get('/v1/config/types')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        global.typeOrganization = 0;
        global.typeUser = 0;
        for (let i = 0; i < response.body.length; i++) {
          if (response.body[i].internalname === 'organization') {
            global.typeOrganization = response.body[i].id;
          } else if (response.body[i].internalname === 'users') {
            global.typeUser = response.body[i].id;
          }
        }
        assert(is.not.equal(0, global.typeOrganization), 'organization type not found');
        assert(is.not.equal(0, global.typeUser), 'user type not found');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete organization type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeOrganization)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot delete this type, it is a system type'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete user type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeUser)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot delete this type, it is a system type'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
