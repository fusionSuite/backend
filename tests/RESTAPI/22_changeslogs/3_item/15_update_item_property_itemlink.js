const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('changes | items | update itemlink type property', function () {
  it('Update itemlink property of the item', function (done) {
    request
      .patch('/v1/items/' + global.itemId + '/property/' + global.properties.itemlink)
      .send(
        {
          value: 1,
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

  it('get the item and check if changes has new entry', function (done) {
    global.changesEntries += 1;
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
        assert(is.equal(global.changesEntries, response.body.changes.length), 'must have ' + global.changesEntries + ' changes');

        const lastChanges = response.body.changes[(global.changesEntries - 1)];
        assert(is.equal('admin', lastChanges.username), 'must have username = admin');
        assert(is.equal('{"id":2,"name":"admin"}', lastChanges.old_value), 'old value is wrong');
        assert(is.equal('{"id":1,"name":"My organization"}', lastChanges.new_value), 'new value is wrong');
        assert(is.equal('admin changed "property with type itemlink" to "My organization"', lastChanges.message), 'wrong message');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table when update property - must have only 1 rows more', function (done) {
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
