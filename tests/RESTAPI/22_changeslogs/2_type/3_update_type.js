const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('changes | types | update a type', function () {
  it('Update name of the type', function (done) {
    request
      .patch('/v1/config/types/' + global.typeId)
      .send(
        {
          name: 'test for a new name of the type',
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

  it('get the type and check if changes has 3 entries', function (done) {
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
        assert(is.equal(3, response.body.changes.length), 'must have 3 changes');
        const firstChanges = response.body.changes[2];
        assert(is.equal('admin', firstChanges.username), 'must have username = admin');
        assert(is.equal('Type for changes', firstChanges.old_value), 'old value is wrong');
        assert(is.equal('test for a new name of the type', firstChanges.new_value), 'new value is wrong');
        assert(is.equal('admin changed name to "test for a new name of the type"', firstChanges.message), 'wrong message');
      })
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
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });
});
