const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('items | Endpoint /v1/items/type/2 (GET all)', function() {
  it('Get the items of the type', function(done) {
    request
    .get('/v1/items/type/2')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'response body must not be empty');
      assert(is.array(response.body), 'response body must be an array');
      firstElement = response.body[0];
      assert(is.propertyCount(firstElement, 8), 'the first item must have 8 object properties');
      assert(is.integer(firstElement.id), 'the item id must be an integer');
      assert(is.integer(firstElement.id_bytype), 'the item id_bytype must be an integer');
      assert(is.string(firstElement.name), 'the item name must be a string');
      assert(is.string(firstElement.created_at), 'the item created_at must be a string');
      assert(is.null(firstElement.parent_id), 'the item parent_id must be a null');
      assert(is.null(firstElement.treepath), 'the item treepath must be a null');
      assert(validator.isISO8601(firstElement.created_at), 'the item created_at must be a valid ISO8601 date');
      okType = false;
      if (is.null(firstElement.updated_at)) {
        okType = true;
      } else if (is.string(firstElement.updated_at)) {
        okType = true;
        assert(validator.isISO8601(firstElement.updated_at), 'the item updated_at must be a valid ISO8601 date');
      }
      assert(okType, 'updated_at must be a string or null');
      assert(is.array(firstElement.properties), 'the item properties must be an array');

      // Test the first property
      $serialnumber = null;
      for (let property of firstElement.properties) {
        if (property.id === global.propertyid) {
          $serialnumber = property;
        }
      }
      assert($serialnumber !== null, 'the item must have a property named serialnumber');
      assert(is.integer($serialnumber.id), 'the first property id must be an integer');
      assert(is.string($serialnumber.name), 'the first property name must be a string');
      assert(is.string($serialnumber.valuetype), 'the first property valuetype must be a string');
      okType = false;
      if (is.null($serialnumber.unit)) {
        okType = true;
      } else if (is.string($serialnumber.unit)) {
        okType = true;
      }
      assert(okType, 'unit must be a string or null');
      assert(is.array($serialnumber.listvalues), 'the first property listvalues must be an array');
      assert(is.string($serialnumber.value), 'the first property value must be a string');
      assert(is.boolean($serialnumber.byfusioninventory), 'the first property byfusioninventory must be a boolean');
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
