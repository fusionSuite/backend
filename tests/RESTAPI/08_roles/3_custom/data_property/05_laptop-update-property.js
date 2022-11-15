const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('roles | custom > data > properties | update properties', function () {
  it('set permission to laptop to update only 1 property: inventory number', function (done) {
    request
      .patch('/v1/config/roles/' + global.roleId + '/permissiondata/' + global.permissiondataId + '/property/' + global.propertyInventorynumberPermissionId)
      .send({
        view: true,
        update: true,
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

  it('get the specific laptop, must view only the 2 properties', function (done) {
    request
      .get('/v1/items/' + global.item1Id)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('myitem1', response.body.name));
        response.body.properties.forEach(property => {
        });
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('Update laptop property inventory number', function (done) {
    request
      .patch('/v1/items/' + global.item1Id + '/property/' + global.propertyInventorynumberPermissionPropId)
      .send({
        value: 'LAP0045',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('Update laptop property serial number => error', function (done) {
    request
      .patch('/v1/items/' + global.item1Id + '/property/' + global.propertySerialNumberPermissionPropId)
      .send({
        value: 'XPRT23GGTR',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(401)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'No permission on this item'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('Update laptop property not exists => error', function (done) {
    request
      .patch('/v1/items/' + global.item1Id + '/property/30003')
      .send({
        value: 'XPRT23GGTR',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(404)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'The property has not be found'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
