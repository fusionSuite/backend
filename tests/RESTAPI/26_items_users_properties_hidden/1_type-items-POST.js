const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('items_users_properties_hidden | Endpoint /v1/items', function () {
  it('create a new user with refreshtoken', function (done) {
    request
      .post('/v1/items')
      .send({ name: 'test_user_001', type_id: 2, properties: [{ property_id: 3, value: 'refresh01' }] })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(is.integer(response.body.id_bytype));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check the refreshtoken not set into database', function (done) {
    requestDB
      .get('/item_property/itemid/' + global.id.toString() + '/propertyid/3')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(1, response.body.count), 'must have only 1 property');
        assert(is.equal('', response.body.rows[0].value_string), 'the refreshtoken must be empty into database');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new user with jwtid property', function (done) {
    request
      .post('/v1/items')
      .send({ name: 'test_user_002', type_id: 2, properties: [{ property_id: 4, value: 'token' }] })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(is.integer(response.body.id_bytype));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.id002 = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check the jwtid not set into database', function (done) {
    requestDB
      .get('/item_property/itemid/' + global.id002.toString() + '/propertyid/4')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(1, response.body.count), 'must have only 1 property');
        assert(is.equal('', response.body.rows[0].value_string), 'the jwtid must be empty into database');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
