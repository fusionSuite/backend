const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('roles | grant | check user have access to items', function() {

  it ('get the token for user user1', function(done) {
    request
    .post('/v1/token')
    .send({login: 'user1', password: 'test'})
    .set('Accept', 'application/json')
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 3));

      assert(validator.isJWT(response.body.token));
      assert(validator.matches(response.body.refreshtoken, /^\w+$/));

      assert(is.integer(response.body.expires));
      assert(validator.matches('' + response.body.expires, /^\d{10}$/));
      global.tokenUser1 = response.body.token;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('create a laptop', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'myitem1',
      type_id: 3,
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.item1Id = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('get laptops', function(done) {
    request
    .get('/v1/items/type/3')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'response body must not be empty');
      assert(is.array(response.body), 'response body must be an array');
      assert(is.propertyCount(response.body, 1), 'must have 1 item');
      assert(is.equal(response.body[0].name, 'myitem1'), 'must have the name item1');
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
