const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('changes | types | delete the type', function () {
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

  it('check changes rows in database table - must have only 1 rows more', function (done) {
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

  it('hard delete a type', function (done) {
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

  it('check changes rows in database table - must have only 3 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect(function (response) {
        global.changesCnt += 3;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
        assert(is.equal(3, response.body.rows.length), 'wrong number of changes');
        // test deleted the property "First name"
        let row = response.body.rows[0];
        assert(is.equal('admin deleted the property "First name"', row.message), '(0) wrong message');
        assert(is.equal('{"id":1,"name":"First name"}', row.old_value), '(0) old value is wrong, must be: ' + row.old_value);
        assert(is.null(row.new_value), '(0) old value is wrong ' + row.new_value);

        // test deleted the second property "Last name"
        row = response.body.rows[1];
        assert(is.equal('admin deleted the property "Last name"', row.message), '(1) wrong message');
        assert(is.equal('{"id":2,"name":"Last name"}', row.old_value), '(1) old value is wrong, must be: ' + row.old_value);
        assert(is.null(row.new_value), '(1) old value is wrong ' + row.new_value);

        // test deleted changes row
        row = response.body.rows[2];
        assert(is.equal('admin deleted this item', row.message), '(2) wrong message');
        // replace dates to be easier to compare
        row.old_value = row.old_value.replace(/("20\d{2}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/g, '"**date**');
        row.old_value = row.old_value.replace(/("20\d{2}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}.000000Z)/g, '"**date**');
        assert(
          is.equal(
            '{"id":' + global.typeId + ',"name":"test for a new name of the type","internalname":"typeforchanges","sub_organization":false,"modeling":"logical","tree":false,"allowtreemultipleroots":false,"unique_name":false,"created_at":"**date**","updated_at":"**date**","deleted_at":"**date**","created_by":{"id":2,"name":"admin","first_name":"Steve","last_name":"Rogers"},"updated_by":{"id":2,"name":"admin","first_name":"Steve","last_name":"Rogers"},"deleted_by":{"id":2,"name":"admin","first_name":"Steve","last_name":"Rogers"},"properties":[{"id":1,"name":"First name","internalname":"userfirstname","valuetype":"string","regexformat":null,"unit":null,"description":null,"canbenull":true,"setcurrentdate":null,"listvalues":[],"default":"","allowedtypes":[]},{"id":2,"name":"Last name","internalname":"userlastname","valuetype":"string","regexformat":null,"unit":null,"description":null,"canbenull":true,"setcurrentdate":null,"listvalues":[],"default":"","allowedtypes":[]}],"organization":{"id":1,"name":"My organization"}}',
            row.old_value,
          ),
          '(2) old value is wrong ' + row.old_value,
        );
        assert(is.equal('hard delete', row.new_value), '(2) new value is wrong');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });
});
