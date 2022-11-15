const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemsHeaders | Endpoint /v1/items', function () {
  for (let step = 1; step <= 15; step++) {
    it('create a new item ' + step, function (done) {
      request
        .post('/v1/items')
        .send({ name: 'Laptop' + step, type_id: 3 })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.propertyCount(response.body, 2));
          assert(is.integer(response.body.id));
          assert(is.integer(response.body.id_bytype));
          assert(validator.matches('' + response.body.id, /^\d+$/));
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });
  }
});
