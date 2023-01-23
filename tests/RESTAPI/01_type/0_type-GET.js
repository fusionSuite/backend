const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('type | Endpoint /v1/config/types', function () {
  it('respond with json containing the list of types', function (done) {
    request
      .get('/v1/config/types')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        const laptopType = response.body[2]; // laptops
        assert(is.propertyCount(laptopType, 16));
        assert(is.number(laptopType.id));
        assert(is.string(laptopType.name));
        assert(is.string(laptopType.internalname));
        assert(is.string(laptopType.modeling));
        assert(is.boolean(laptopType.tree));
        assert(is.boolean(laptopType.allowtreemultipleroots));
        assert(validator.isISO8601(laptopType.created_at));
        assert(validator.isISO8601(laptopType.updated_at));
        assert(is.array(laptopType.properties));

        assert(validator.equals('' + laptopType.id, '3'));
        assert(validator.equals(laptopType.name, 'Laptop'));
        assert(validator.equals(laptopType.modeling, 'physical'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
