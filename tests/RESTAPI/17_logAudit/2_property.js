const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('logAudit | log property actions', function() {
  it('truncate audits database table', function(done) {
    requestDB
    .get('/truncate/audits')
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a property', function(done) {
    request
    .post('/v1/config/properties')
    .send({
      name: 'myprop1',
      internalname: 'testformypropone',
      organization_id: 1,
      valuetype: 'string',
      listvalues: [],
      default: ''
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.myprop1 = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a property with wrong values', function(done) {
    request
    .post('/v1/config/properties')
    .send({
      name: 'myprop1',
      internalname: 'testformypropone%t',
      organization_id: 1,
      valuetype: true,
      listvalues: [],
      default: ''
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('view a property', function(done) {
    request
    .get('/v1/config/properties/'+global.myprop1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.equal('myprop1', response.body.name));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('view a property does not exists', function(done) {
    request
    .get('/v1/config/properties/300000')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(404)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('update a property', function(done) {
    request
    .patch('/v1/config/properties/'+global.myprop1)
    .send({
      name: 'test patch'
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

  it('update a property with error', function(done) {
    request
    .patch('/v1/config/properties/'+global.myprop1)
    .send({
      setcurrentdate: 'string instead boolean'
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('softdelete a property', function(done) {
    request
    .delete('/v1/config/properties/'+global.myprop1)
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

  it('restore a property', function(done) {
    request
    .patch('/v1/config/properties/'+global.myprop1)
    .send({
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

  it('softdelete a property', function(done) {
    request
    .delete('/v1/config/properties/'+global.myprop1)
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

  it('delete a property', function(done) {
    request
    .delete('/v1/config/properties/'+global.myprop1)
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

  it('delete a property does not exists', function(done) {
    request
    .delete('/v1/config/properties/30000000')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(404)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('verify audits added', function(done) {
    request
    .get('/v1/log/audits')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body));
      assert(is.equal(10, response.body.length));

      createProperty = response.body[0];
      assert(is.equal(200, createProperty.httpcode));
      assert(is.equal('CREATE', createProperty.action));
      assert(is.equal('', createProperty.message));

      failCreateProperty = response.body[1];
      assert(is.equal(400, failCreateProperty.httpcode));
      // assert(is.equal('CREATE', failCreateProperty.action));
      assert(is.equal('The Internalname is not valid format, The Valuetype is not valid type', failCreateProperty.message));

      viewNotexistsProperty = response.body[2];
      assert(is.equal(404, viewNotexistsProperty.httpcode));
      // assert(is.equal('VIEW', viewNotexistsProperty.action));
      assert(is.equal('This item has not be found', viewNotexistsProperty.message));

      updateProperty = response.body[3];
      assert(is.equal(200, updateProperty.httpcode));
      assert(is.equal('UPDATE', updateProperty.action));
      assert(is.equal('', updateProperty.message));

      failUpdateProperty = response.body[4];
      assert(is.equal(400, failUpdateProperty.httpcode));
      // assert(is.equal('UPDATE', failUpdateProperty.action));
      assert(is.equal('The Setcurrentdate is not valid type', failUpdateProperty.message));

      softdeleteProperty = response.body[5];
      assert(is.equal(200, softdeleteProperty.httpcode));
      assert(is.equal('SOFTDELETE', softdeleteProperty.action));
      assert(is.equal('', softdeleteProperty.message));

      restoreProperty = response.body[6];
      assert(is.equal(200, restoreProperty.httpcode));
      assert(is.equal('SOFTDELETE', restoreProperty.action));
      assert(is.equal('restore', restoreProperty.message));

      softdeletePropertyBis = response.body[7];
      assert(is.equal(200, softdeletePropertyBis.httpcode));
      assert(is.equal('SOFTDELETE', softdeletePropertyBis.action));
      assert(is.equal('', softdeletePropertyBis.message));

      deleteProperty = response.body[8];
      assert(is.equal(200, deleteProperty.httpcode));
      assert(is.equal('DELETE', deleteProperty.action));
      assert(is.equal('', deleteProperty.message));

      faildeleteProperty = response.body[9];
      assert(is.equal(404, faildeleteProperty.httpcode));
      // assert(is.equal('DELETE', faildeleteProperty.action));
      assert(is.equal('The property has not be found', faildeleteProperty.message));

    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

});