const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemsTreeMultipleRoots | create root item', function () {
  it('create the root item', function (done) {
    request
      .post('/v1/items')
      .send({
        name: 'my top level of the tree',
        type_id: global.typeId,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(is.integer(response.body.id_bytype));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        assert(is.equal(1, response.body.id_bytype));
        global.itemLevel1Id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('verify the root item information', function (done) {
    request
      .get('/v1/items/' + global.itemLevel1Id)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'The body must contain something');
        assert(is.object(response.body), 'the body response must be an object');
        assert(is.equal('my top level of the tree', response.body.name));
        assert(is.equal('0001', response.body.treepath));
        assert(is.string(response.body.treepath));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
