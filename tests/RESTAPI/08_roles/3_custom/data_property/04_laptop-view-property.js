const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('roles | custom > data > properties | view the serial number property only', function () {
  it('get the specific laptop, must not view any properties', function (done) {
    request
      .get('/v1/items/' + global.item1Id)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('myitem1', response.body.name));
        assert(is.equal(0, response.body.properties.length));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('set permission to laptop to view only 1 property: serial number', function (done) {
    request
      .patch('/v1/config/roles/' + global.roleId + '/permissiondata/' + global.permissiondataId + '/property/' + global.propertySerialNumberPermissionId)
      .send({
        view: true,
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

  it('get the specific laptop, must view only 1 property: serial number', function (done) {
    request
      .get('/v1/items/' + global.item1Id)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('myitem1', response.body.name));
        assert(is.equal(1, response.body.properties.length));
        assert(is.equal('Serial number', response.body.properties[0].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get the all laptops, must view only 1 property: serial number', function (done) {
    request
      .get('/v1/items/type/3')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'response body must not be empty');
        assert(is.array(response.body), 'response body must be an array');
        assert(is.above(response.body.length, 0), 'response body must have at least 1 item');
        response.body.forEach(item => {
          assert(is.equal(1, item.properties.length));
          assert(is.equal('Serial number', item.properties[0].name));
        });
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
