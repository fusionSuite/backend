const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('organizations | items | get items with admin', function () {
  it('get all items in top level of oganization', function (done) {
    request
      .get('/v1/items/type/' + global.typeId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'response body must not be empty');
        assert(is.array(response.body), 'response body must be an array');
        assert(is.propertyCount(response.body, 4), 'must have the 4 items');
        // check the organization field in items
        const firstItem = response.body[0];
        assert(is.object(firstItem.organization));
        assert(is.equal(2, Object.keys(firstItem.organization).length), 'organization must have only 2 attributes');
        assert(is.equal(1, firstItem.organization.id), 'organization id must be 1');
        assert(is.equal('My organization', firstItem.organization.name), 'organization name must be `My organization`');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
