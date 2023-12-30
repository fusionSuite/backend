const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('changes | items | update an item', function () {
  it('Update name of the item', function (done) {
    request
      .patch('/v1/items/' + global.itemId)
      .send(
        {
          name: 'laptop yyy6yy',
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

  it('get the item and check if changes has one entry', function (done) {
    request
      .get('/v1/items/' + global.itemId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('laptop yyy6yy', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
        assert(is.equal(1, response.body.changes.length), 'must have 1 changes');
        const firstChanges = response.body.changes[0];
        assert(is.equal('admin', firstChanges.username), 'must have username = admin');
        assert(is.equal('laptop xxx5xx', firstChanges.old_value), 'old value is wrong');
        assert(is.equal('laptop yyy6yy', firstChanges.new_value), 'new value is wrong');
        assert(is.equal('admin changed name to "laptop yyy6yy"', firstChanges.message), 'wrong message');

        assert(is.equal(4, Object.keys(firstChanges.user).length), 'user must have 4 attributes: id, name, first_name, last_name');
        assert(is.equal(2, firstChanges.user.id, 'user must be filled with admin id'));
        assert(is.equal('admin', firstChanges.user.name, 'user.name must be filled with `admin'));
        assert(is.equal('Steve', firstChanges.user.first_name, 'user.first_name must be `Steve`'));
        assert(is.equal('Rogers', firstChanges.user.last_name, 'user.last_name must be `Rogers`'));
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
