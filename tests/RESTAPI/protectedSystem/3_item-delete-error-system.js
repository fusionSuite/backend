const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/

describe('protectedSystem | delete system item give an error', function() {

  it ('get system items', function(done) {
    request
    .get('/v1/items/type/'+global.typeOrganization)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      global.itemOrganization = 0;
      for (let i = 0; i < response.body.length; i++) {
        if (response.body[i].treepath === '0001') {
          global.itemOrganization = response.body[i].id;
        }
      }
      assert(is.not.equal(0, global.itemOrganization), 'first organization item not found');
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('try delete the first organization item', function(done) {
    request
    .delete('/v1/items/'+global.itemOrganization)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(403)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'Cannot delete this item, it is a system item'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
