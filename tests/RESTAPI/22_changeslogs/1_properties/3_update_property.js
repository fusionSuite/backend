const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('changes | properties | update a property', function () {
  it('Update name of the property', function (done) {
    request
      .patch('/v1/config/properties/' + global.propertyId)
      .send(
        {
          name: 'test for a new name of the property',
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

  it('get the property and check if changes has one entry', function (done) {
    request
      .get('/v1/config/properties/' + global.propertyId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('test for a new name of the property', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
        assert(is.equal(1, response.body.changes.length), 'must have 1 changes');
        const firstChanges = response.body.changes[0];
        assert(is.equal('admin', firstChanges.username), 'must have username = admin');
        assert(is.equal('Test for string', firstChanges.old_value), 'old value is wrong');
        assert(is.equal('test for a new name of the property', firstChanges.new_value), 'new value is wrong');
        assert(is.equal('admin changed name to "test for a new name of the property"', firstChanges.message), 'wrong message');
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

  it('Update name, unit and canbenull of the property', function (done) {
    request
      .patch('/v1/config/properties/' + global.propertyId)
      .send({
        name: 'original name ;)',
        unit: 'smileys',
        canbenull: true,
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

  it('get the property and check if changes has 4 entries', function (done) {
    request
      .get('/v1/config/properties/' + global.propertyId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('original name ;)', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
        assert(is.equal(4, response.body.changes.length), 'must have 4 changes');
        const firstChanges = response.body.changes[0];
        assert(is.equal('admin', firstChanges.username), 'must have username = admin');
        assert(is.equal('Test for string', firstChanges.old_value), 'old value is wrong');
        assert(is.equal('test for a new name of the property', firstChanges.new_value), 'new value is wrong');
        assert(is.equal('admin changed name to "test for a new name of the property"', firstChanges.message), 'wrong message');

        const secondChanges = response.body.changes[1];
        assert(is.equal('admin', secondChanges.username), 'second - must have username = admin');
        assert(is.equal('test for a new name of the property', secondChanges.old_value), 'second - old value is wrong');
        assert(is.equal('original name ;)', secondChanges.new_value), 'second - new value is wrong');
        assert(is.equal('admin changed name to "original name ;)"', secondChanges.message), 'second - wrong message');

        const thirdChanges = response.body.changes[2];
        assert(is.equal('admin', thirdChanges.username), 'third - must have username = admin');
        assert(is.equal('', thirdChanges.old_value), 'third - old value is wrong');
        assert(is.equal('smileys', thirdChanges.new_value), 'third - new value is wrong');
        assert(is.equal('admin changed unit to "smileys"', thirdChanges.message), 'third - wrong message');

        const fourthChanges = response.body.changes[3];
        assert(is.equal('admin', fourthChanges.username), 'fourth - must have username = admin');
        assert(is.equal('false', fourthChanges.old_value), 'fourth - old value is wrong');
        assert(is.equal('true', fourthChanges.new_value), 'fourth - new value is wrong');
        assert(is.equal('admin changed canbenull to "true"', fourthChanges.message), 'fourth - wrong message');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table - must have only 3 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt += 3;
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
