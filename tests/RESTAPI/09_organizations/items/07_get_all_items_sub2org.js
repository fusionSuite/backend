const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('organizations | items | get items with user2', function () {
  it('get all items in sub1 level of oganization', function (done) {
    request
      .get('/v1/items/type/' + global.typeId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser2)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'response body must not be empty');
        assert(is.array(response.body), 'response body must be an array');
        assert(is.propertyCount(response.body, 2), 'must have the third item');
        assert(is.equal(response.body[0].id, global.myitemSub1), 'must have the id of the myitemSub1');
        assert(is.equal(response.body[1].id, global.myitem3), 'must have the id of the myitem3');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
