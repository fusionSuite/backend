const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('items | patch item property /v1/items/xx/property/zz', function() {

  it('it update an item property', function(done) {
    request
    .patch('/v1/items/' + global.id + '/property/' + global.propertyid)
    .send({value:"serialyyyyyy687"})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 0));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('Get the item with properties', function(done) {
    request
    .get('/v1/items/' + global.id)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body.properties));
      assert(is.equal(4, response.body.properties.length))

      // Test the property with serial
      let serialPropertyFound = false;
      for (let prop of response.body.properties) {
        if (prop.id === 5) {
          assert(is.integer(prop.id));
          assert(is.string(prop.value));
          assert(validator.equals(prop.value, 'serialyyyyyy687'));
          serialPropertyFound = true;
        }
      }
      assert(serialPropertyFound);
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

});
