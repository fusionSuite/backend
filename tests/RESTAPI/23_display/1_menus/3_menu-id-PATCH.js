const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display menu | Patch menu', function () {
  it('update the menu', function (done) {
    request
      .patch('/v1/display/menu/' + global.id)
      .send({ name: 'New assets' })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 0));
      })
      .end(function (err, response) {
        if (err) {
          console.log(err);
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('update the Assets menu, but forget name => ok', function (done) {
    request
      .patch('/v1/display/menu/' + global.id)
      .send({})
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

  it('update the Assets menu, but name not in right type => error', function (done) {
    request
      .patch('/v1/display/menu/' + global.id)
      .send({ name: true })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(400)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'The Name is not valid type'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
