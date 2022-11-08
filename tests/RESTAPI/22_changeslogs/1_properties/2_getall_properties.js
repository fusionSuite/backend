const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('changes | properties | get all properties', function () {
  it('get all properties and check changes key not exists', function (done) {
    request
      .get('/v1/config/properties')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.above(response.body.length, 2));

        const firstProperty = response.body[0];
        assert(is.not.propertyDefined(firstProperty, 'changes'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
