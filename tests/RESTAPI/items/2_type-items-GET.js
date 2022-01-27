const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('Endpoint /v1/items/type/2 (GET all)', function() {
  it('Get the items of the type', function(done) {
    request
    .get('/v1/items/type/2')
    .send({name: 'L0014',properties:[{property_id:3,value:"serialxxxxxx"}]})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body));

      firstElement = response.body[0];
      assert(is.propertyCount(firstElement, 5));
      assert(is.integer(firstElement.id));
      assert(is.string(firstElement.name));
      assert(is.string(firstElement.created_at));
      assert(validator.isISO8601(firstElement.created_at));
      okType = false;
      if (is.null(firstElement.updated_at)) {
        okType = true;
      } else if (is.string(firstElement.updated_at)) {
        okType = true;
        assert(validator.isISO8601(firstElement.updated_at));
      }
      assert(okType, 'updated_at must be a string or null');
      assert(is.array(firstElement.properties));

      // Test the first property
      firstProperty = firstElement.properties[0];
      assert(is.integer(firstProperty.id));
      assert(is.string(firstProperty.name));
      assert(is.string(firstProperty.valuetype));
      okType = false;
      if (is.null(firstProperty.unit)) {
        okType = true;
      } else if (is.string(irstProperty.unit)) {
        okType = true;
      }
      assert(okType, 'unit must be a string or null');
      assert(is.array(firstProperty.listvalues));
      assert(is.string(firstProperty.value));
      assert(is.boolean(firstProperty.byfusioninventory));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
