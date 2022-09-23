const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('items_users_properties_hidden | Endpoint /v1/items/type/ users (GET all)', function () {
  it('Get the items of the type users', function (done) {
    request
      .get('/v1/items/type/2')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'response body must not be empty');
        assert(is.array(response.body), 'response body must be an array');
        const firstElement = response.body[0];
        assert(is.array(firstElement.properties), 'the item properties must be an array');

        const propertiesIds = [];
        let password = null;
        for (const property of firstElement.properties) {
          propertiesIds.push(property.id);
          if (property.id === 5) {
            password = property.value;
          }
        }

        assert.deepEqual(propertiesIds, [1, 2, 5, 6], 'We must have only first name, last name, empty password and activated properties');
        assert(is.null(password), 'the password must be always null value when get, defined or not in the database');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
