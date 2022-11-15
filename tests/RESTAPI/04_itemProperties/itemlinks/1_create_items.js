const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

global.itemId1 = 0;
global.itemId2 = 0;
global.itemId3 = 0;
global.itemId4 = 0;
global.itemId5 = 0;
global.itemId6 = 0;

describe('itemProperties: itemlinks type | create items', function () {
  for (let i = 1; i < 7; i++) {
    it('create an item ' + i, function (done) {
      request
        .post('/v1/items')
        .send({
          name: 'my item ' + i,
          type_id: 3,
        })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.propertyCount(response.body, 2));
          assert(is.integer(response.body.id));
          assert(is.integer(response.body.id_bytype));
          assert(validator.matches('' + response.body.id, /^\d+$/));
          global['itemId' + i] = response.body.id;
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
