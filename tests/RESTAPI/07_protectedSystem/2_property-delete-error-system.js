const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('protectedSystem | delete system properties give an error', function () {
  it('get system properties', function (done) {
    request
      .get('/v1/config/properties')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        global.propertyFirstname = 0;
        global.propertyLastname = 0;
        global.propertyRefreshtoken = 0;
        global.propertyJwtid = 0;
        global.propertyActivated = 0;
        for (let i = 0; i < response.body.length; i++) {
          if (response.body[i].internalname === 'userfirstname') {
            global.propertyFirstname = response.body[i].id;
          } else if (response.body[i].internalname === 'userlastname') {
            global.propertyLastname = response.body[i].id;
          } else if (response.body[i].internalname === 'userrefreshtoken') {
            global.propertyRefreshtoken = response.body[i].id;
          } else if (response.body[i].internalname === 'userjwtid') {
            global.propertyJwtid = response.body[i].id;
          } else if (response.body[i].internalname === 'activated') {
            global.propertyActivated = response.body[i].id;
          }
        }
        assert(is.not.equal(0, global.propertyFirstname), 'firstname property not found');
        assert(is.not.equal(0, global.propertyLastname), 'lastname property not found');
        assert(is.not.equal(0, global.propertyRefreshtoken), 'refreshtoken property not found');
        assert(is.not.equal(0, global.propertyJwtid), 'jwtid property not found');
        assert(is.not.equal(0, global.propertyActivated), 'activated property not found');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete the firstname property from user type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeUser + '/property/' + global.propertyFirstname)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot detach this property, it is a system property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete the lastname property from user type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeUser + '/property/' + global.propertyLastname)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot detach this property, it is a system property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete the refreshtoken property from user type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeUser + '/property/' + global.propertyRefreshtoken)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot detach this property, it is a system property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete the jwtid property from user type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeUser + '/property/' + global.propertyJwtid)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot detach this property, it is a system property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete the activated property from user type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeUser + '/property/' + global.propertyActivated)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot detach this property, it is a system property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('add the firstname property to organization type', function (done) {
    request
      .post('/v1/config/types/' + global.typeOrganization + '/property/' + global.propertyFirstname)
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

  it('delete the firstname property to organization type with success', function (done) {
    request
      .delete('/v1/config/types/' + global.typeOrganization + '/property/' + global.propertyFirstname)
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

  it('try delete the firstname property', function (done) {
    request
      .delete('/v1/config/properties/' + global.propertyFirstname)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot delete this property, it is a system property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete the lastname property', function (done) {
    request
      .delete('/v1/config/properties/' + global.propertyLastname)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot delete this property, it is a system property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete the refreshtoken property', function (done) {
    request
      .delete('/v1/config/properties/' + global.propertyRefreshtoken)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot delete this property, it is a system property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete the jwtid property', function (done) {
    request
      .delete('/v1/config/properties/' + global.propertyJwtid)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot delete this property, it is a system property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try delete the activated property', function (done) {
    request
      .delete('/v1/config/properties/' + global.propertyActivated)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(403)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Cannot delete this property, it is a system property'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
