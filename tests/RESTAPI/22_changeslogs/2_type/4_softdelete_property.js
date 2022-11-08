const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('changes | types | soft delete type', function () {
  it('soft delete a type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeId)
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

  it('get the type and check if changes has 4 entries', function (done) {
    request
      .get('/v1/config/types/' + global.typeId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('test for a new name of the type', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
        assert(is.equal(4, response.body.changes.length), 'must have 4 changes');

        const lastChanges = response.body.changes[3];
        assert(is.equal('admin', lastChanges.username), 'fifth - must have username = admin');
        assert(is.equal('', lastChanges.old_value), 'fifth - old value is wrong');
        assert(is.equal('soft delete', lastChanges.new_value), 'fifth - new value is wrong');
        assert(is.equal('admin soft deleted this item', lastChanges.message), 'fifth - wrong message');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table when soft delete - must have only 1 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt += 1;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('restore a soft delete type', function (done) {
    request
      .patch('/v1/config/types/' + global.typeId)
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

  it('get the type and check if changes has 5 entries', function (done) {
    request
      .get('/v1/config/types/' + global.typeId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('test for a new name of the type', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
        assert(is.equal(5, response.body.changes.length), 'must have 5 changes');

        const lastChanges = response.body.changes[4];
        assert(is.equal('admin', lastChanges.username), 'sixth - must have username = admin');
        assert(is.equal('', lastChanges.old_value), 'sixth - old value is wrong');
        assert(is.equal('restored', lastChanges.new_value), 'sixth - new value is wrong');
        assert(is.equal('admin restored this item', lastChanges.message), 'sixth - wrong message');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table when restore - must have only 1 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt += 1;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });
});
