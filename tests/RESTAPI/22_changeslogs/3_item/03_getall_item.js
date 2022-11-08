const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('changes | items | get all items', function () {
  it('get all items and check changes key not exists', function (done) {
    request
      .get('/v1/items/type/' + global.typeId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.above(response.body.length, 0));

        const firstItem = response.body[0];
        assert(is.not.propertyDefined(firstItem, 'changes'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
