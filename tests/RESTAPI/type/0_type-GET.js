const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('type | Endpoint /v1/config/types', function() {
  it('respond with json containing the list of types', function(done) {
    request
    .get('/v1/config/types')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      const secondItem = response.body[1];
      assert(is.propertyCount(secondItem, 10));
      assert(is.number(secondItem.id));
      assert(is.string(secondItem.name));
      assert(is.string(secondItem.internalname));
      assert(is.string(secondItem.modeling));
      assert(is.boolean(secondItem.tree));
      assert(is.boolean(secondItem.allowtreemultipleroots));
      assert(validator.isISO8601(secondItem.created_at));
      assert(validator.isISO8601(secondItem.updated_at));
      assert(is.array(secondItem.properties));
      assert(is.array(secondItem.propertygroups));

      assert(validator.equals('' + secondItem.id, '2'));
      assert(validator.equals(secondItem.name, 'Laptop'));
      assert(validator.equals(secondItem.modeling, 'physical'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});

