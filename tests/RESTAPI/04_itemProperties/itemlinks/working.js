const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties: itemlinks type - working set', function() {
  return;
  common.defineValuetype('itemlinks');
  common.createType();
  common.createProperty(null);
  common.attachPropertyToType();

  it('create a new item (no itemlink)', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'test itemlinks',
      type_id: global.propertytypesid,
      properties:[]
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
      global.itemId = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new item (with itemlink itemlink)', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'test itemlinks',
      type_id: global.propertytypesid,
      properties:[
        {
          property_id: global.propertyvaluesid,
          value: [global.itemId]
        }
      ]
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
      global.itemId2 = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('Get the item of the type and verify the default value (itemlinks) is right', function(done) {
    request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      firstElement = response.body[0];
      assert(is.propertyCount(firstElement.properties, 0));
      secondElement = response.body[1];
      assert(is.propertyCount(secondElement.properties, 1));
      for (let prop of secondElement.properties) {
        assert(is.array(prop.value), 'the property value must be an array of items');
        for (let item of prop.value) {
          assert(is.object(item), 'the property value item must be an itemlinks type');
          assert(is.propertyDefined(item, 'name'), 'the property value item must be an object and must have name property');
          assert(is.propertyDefined(item, 'id'), 'the property value item must be an object and must have id property');
          assert(is.equal(item.id, global.itemId), 'the property value item must be an object and the id is ' + global.itemId.toString());
          assert(is.equal(item.name, 'test itemlinks'), 'the property value item must be an object and the name is test itemlinks');
        }
      }
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  common.deleteItem();
  common.deleteType();
  common.deleteProperty();
});
