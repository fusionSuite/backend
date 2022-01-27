const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('Delete  a item property /v1/items/xx/property/zz', function() {

  it('it delete an item property', function(done) {
    request
    .delete('/v1/items/' + global.id + '/property/' + global.propertyid)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('Get the item with no properties', function(done) {
    request
    .get('/v1/items/' + global.id)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body.properties));
      assert(is.equal(0, response.body.properties.length))
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

});
