const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('changes | items | delete the item', function () {
  it('soft delete an item', function (done) {
    request
      .delete('/v1/items/' + global.itemId)
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

  it('check changes rows in database table when soft delete - must have only 1 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
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

  it('hard delete an item', function (done) {
    request
      .delete('/v1/items/' + global.itemId)
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

  it('check changes rows in database table when hard delete - must have only 1 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect(function (response) {
        global.changesCnt += 1;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);

        // test deleted changes row
        assert(is.equal(1, response.body.rows.length), 'wrong number of changes');
        assert(is.equal('admin deleted this item', response.body.rows[0].message), 'wrong message');
        // replace dates to be easier to compare
        response.body.rows[0].old_value = response.body.rows[0].old_value.replace(/("20\d{2}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/g, '"**date**');
        response.body.rows[0].old_value = response.body.rows[0].old_value.replace(/("20\d{2}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}.000000Z)/g, '"**date**');
        assert(
          is.equal(
            '{"id":' + global.itemId + ',"name":"laptop yyy6yy","id_bytype":1,"sub_organization":false,"parent_id":null,"treepath":null,"created_at":"**date**","updated_at":"**date**","deleted_at":"**date**","created_by":{"id":2,"name":"admin","first_name":"Steve","last_name":"Rogers"},"updated_by":{"id":2,"name":"admin","first_name":"Steve","last_name":"Rogers"},"deleted_by":{"id":2,"name":"admin","first_name":"Steve","last_name":"Rogers"},"properties":[],"organization":{"id":1,"name":"My organization"}}',
            response.body.rows[0].old_value,
          ),
          'old value is wrong ' + response.body.rows[0].old_value,
        );
        assert(is.equal('hard delete', response.body.rows[0].new_value), 'new value is wrong');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('Soft delete the user1', function (done) {
    request
      .delete('/v1/items/' + global.user1.toString())
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect('Content-Type', /json/)
      .expect(200)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('Hard delete the user1', function (done) {
    request
      .delete('/v1/items/' + global.user1.toString())
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect('Content-Type', /json/)
      .expect(200)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
