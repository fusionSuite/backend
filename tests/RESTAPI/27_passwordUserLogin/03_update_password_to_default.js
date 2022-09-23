const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('password user login | update password to default', function () {
  it('update password', function (done) {
    request
      .patch('/v1/items/' + global.user1 + '/property/5')
      .send({
        value: null,
        reset_to_default: true,
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

  it('try login but with old password => error', function (done) {
    request
      .post('/v1/token')
      .send({ login: 'user1', password: 'the new PassWoRd' })
      .set('Accept', 'application/json')
      .expect(401)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Error when authentication, login or password not right'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('try login but with null password => error', function (done) {
    request
      .post('/v1/token')
      .send({ login: 'user1', password: null })
      .set('Accept', 'application/json')
      .expect(401)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'Error when authentication, login or password not right'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get password from database and it must be null', function (done) {
    requestDB
      .get('/item_property/itemid/' + global.user1 + '/propertyid/5')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(1, response.body.count), 'must have only 1 property');
        assert(is.null(response.body.rows[0].value_passwordhash), 'the passwordhash must be null');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
