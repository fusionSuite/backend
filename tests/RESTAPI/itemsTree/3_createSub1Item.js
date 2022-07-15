const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemsTree | create sub1 item', function() {
  it('create the sub1 item', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'my second level of the tree',
      type_id: global.typeId,
      parent_id: global.itemLevel1Id,
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(is.integer(response.body.id));
      assert(is.integer(response.body.id_bytype));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      assert(is.equal(2, response.body.id_bytype));
      global.itemLevel2Id = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('verify the sub1 item information', function(done) {
    request
    .get('/v1/items/'+global.itemLevel2Id)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.object(response.body), 'the body response must be an object');
      assert(is.equal('my second level of the tree', response.body.name));
      assert(is.equal(global.itemLevel1Id, response.body.parent_id));
      assert(is.equal('00010002', response.body.treepath));
      assert(is.string(response.body.treepath));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
