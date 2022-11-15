const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemPropertyWhenAddPropertyToType | delete property to type', function () {
  it('Attach a property to the type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeId.toString() + '/property/7')
      .send()
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('verify the item not have right the property 7', function (done) {
    request
      .get('/v1/items/' + global.itemId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'The body must contain something');
        assert(is.object(response.body), 'the body response must be an object');
        assert(is.equal(1, response.body.properties.length), 'must have 1 property');
        assert(is.equal(6, response.body.properties[0].id), 'the id of the unique property must be 6');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
