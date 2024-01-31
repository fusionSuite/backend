const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('changes | types | create a type', function () {
  it('initial number of changes rows in database table', function (done) {
    requestDB
      .get('/count/changes')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt = response.body.count;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('create a new type', function (done) {
    request
      .post('/v1/config/types')
      .send(
        {
          name: 'Type for changes',
          internalname: 'typeforchanges',
        })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.typeId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get the type and check if changes empty', function (done) {
    request
      .get('/v1/config/types/' + global.typeId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('Type for changes', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table when add first property - must not have more changes', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(global.changesCnt, response.body.count));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('Attach the property "First name" to the type', function (done) {
    request
      .post('/v1/config/types/' + global.typeId.toString() + '/property/1')
      .send()
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {})
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table - must have only 1 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt += 1;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
        assert(is.equal('admin added the property "First name"', response.body.rows[0].message), 'wrong message');

        assert(is.null(response.body.rows[0].old_value), 'old value is wrong ' + response.body.rows[0].old_value);
        assert(is.equal('{"id":1,"name":"First name"}', response.body.rows[0].new_value), 'new value is wrong, must be: ' + response.body.rows[0].new_value);
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('Attach the property "Last name" to the type', function (done) {
    request
      .post('/v1/config/types/' + global.typeId.toString() + '/property/2')
      .send()
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {})
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table when add the second property - must have only 1 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt += 1;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
        assert(is.equal('admin added the property "Last name"', response.body.rows[0].message), 'wrong message');

        assert(is.null(response.body.rows[0].old_value), 'old value is wrong ' + response.body.rows[0].old_value);
        assert(is.equal('{"id":2,"name":"Last name"}', response.body.rows[0].new_value), 'new value is wrong, must be: ' + response.body.rows[0].new_value);
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });
});
