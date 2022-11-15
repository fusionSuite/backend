const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemsTreeMultipleRoots | create type', function () {
  it('create a new type', function (done) {
    request
      .post('/v1/config/types')
      .send({
        name: 'testTreeMultipleRoots',
        tree: true,
        allowtreemultipleroots: true,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.typeId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('verify the type is right defined as a tree', function (done) {
    request
      .get('/v1/config/types/' + global.typeId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'The body must contain something');
        assert(is.object(response.body), 'the body response must be an object');
        assert(is.boolean(response.body.tree));
        assert(is.boolean(response.body.allowtreemultipleroots));
        assert(response.body.tree);
        assert(response.body.allowtreemultipleroots);
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
