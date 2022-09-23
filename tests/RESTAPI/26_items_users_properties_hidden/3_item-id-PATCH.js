const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('items_users_properties_hidden | Update /v1/items/:id', function () {
  it('it update an item property: tokenrefresh', function (done) {
    request
      .patch('/v1/items/' + global.id + '/property/' + 3)
      .send({ value: 'try update token' })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(401)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'No permission on this property'));
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

  it('it update an item property: jwtid', function (done) {
    request
      .patch('/v1/items/' + global.id + '/property/' + 4)
      .send({ value: 'try update token' })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(401)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'No permission on this property'));
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
