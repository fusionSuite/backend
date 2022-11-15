const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('logAudit | log type actions', function () {
  it('truncate audits database table', function (done) {
    requestDB
      .get('/truncate/audits')
      .expect(200)
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
        name: 'mytype1',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.mytype1 = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a type with wrong values', function (done) {
    request
      .post('/v1/config/types')
      .send({
        name: 'mytype1',
        organization_id: 'testformytypeone',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(400)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('view a type', function (done) {
    request
      .get('/v1/config/types/' + global.mytype1)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('mytype1', response.body.name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('view a type does not exists', function (done) {
    request
      .get('/v1/config/types/300000')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(404)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('update a type', function (done) {
    request
      .patch('/v1/config/types/' + global.mytype1)
      .send({
        name: 'test patch',
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

  it('update a type with error', function (done) {
    request
      .patch('/v1/config/types/' + global.mytype1)
      .send({
        name: true,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(400)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('softdelete a type', function (done) {
    request
      .delete('/v1/config/types/' + global.mytype1)
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

  it('restore a type', function (done) {
    request
      .patch('/v1/config/types/' + global.mytype1)
      .send({})
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

  it('soft delete a type', function (done) {
    request
      .delete('/v1/config/types/' + global.mytype1)
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

  it('hard delete a type', function (done) {
    request
      .delete('/v1/config/types/' + global.mytype1)
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

  it('delete a type does not exists', function (done) {
    request
      .delete('/v1/config/types/30000000')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(404)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('verify audits added', function (done) {
    request
      .get('/v1/log/audits')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.array(response.body));
        assert(is.equal(10, response.body.length));

        const createType = response.body[0];
        assert(is.equal(200, createType.httpcode));
        assert(is.equal('CREATE', createType.action));
        assert(is.equal('', createType.message));

        const failCreateType = response.body[1];
        assert(is.equal(400, failCreateType.httpcode));
        // assert(is.equal('CREATE', failCreateType.action));
        assert(is.equal('The Organization id is not valid type, The Organization id must be integer', failCreateType.message));

        const viewNotexistsType = response.body[2];
        assert(is.equal(404, viewNotexistsType.httpcode));
        // assert(is.equal('VIEW', viewNotexistsType.action));
        assert(is.equal('This type has not be found', viewNotexistsType.message));

        const updateType = response.body[3];
        assert(is.equal(200, updateType.httpcode));
        assert(is.equal('UPDATE', updateType.action));
        assert(is.equal('', updateType.message));

        const failUpdateType = response.body[4];
        assert(is.equal(400, failUpdateType.httpcode));
        // assert(is.equal('UPDATE', failUpdateType.action));
        assert(is.equal('The Name is not valid type', failUpdateType.message));

        const softdeleteType = response.body[5];
        assert(is.equal(200, softdeleteType.httpcode));
        assert(is.equal('SOFTDELETE', softdeleteType.action));
        assert(is.equal('', softdeleteType.message));

        const restoreType = response.body[6];
        assert(is.equal(200, restoreType.httpcode));
        assert(is.equal('SOFTDELETE', restoreType.action));
        assert(is.equal('restore', restoreType.message));

        const softdeleteTypeBis = response.body[7];
        assert(is.equal(200, softdeleteTypeBis.httpcode));
        assert(is.equal('SOFTDELETE', softdeleteTypeBis.action));
        assert(is.equal('', softdeleteTypeBis.message));

        const deleteType = response.body[8];
        assert(is.equal(200, deleteType.httpcode));
        assert(is.equal('DELETE', deleteType.action));
        assert(is.equal('', deleteType.message));

        const faildeleteType = response.body[9];
        assert(is.equal(404, faildeleteType.httpcode));
        // assert(is.equal('DELETE', faildeleteType.action));
        assert(is.equal('The type has not be found', faildeleteType.message));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
