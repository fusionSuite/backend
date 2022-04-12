const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties: itemlinks type - bad value type when create an item', function() {
  return;

  common.defineValuetype('itemlinks');
  common.createType();
  common.createProperty(null);
  common.attachPropertyToType();

  it('create a new item with bad value (item id does not exist)', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'test itemlinks',
      type_id: global.propertytypesid,
      properties:[
        {
          property_id: global.propertyvaluesid,
          value: 5464
        }
      ]
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Value must be array (property Test for itemlinks - ' + global.propertyvaluesid.toString() + ')'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new item with bad value (item id does not exist)', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'test itemlinks',
      type_id: global.propertytypesid,
      properties:[
        {
          property_id: global.propertyvaluesid,
          value: [5464]
        }
      ]
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Value is an id than does not exist (property Test for itemlinks - ' + global.propertyvaluesid.toString() + ')'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  common.deleteType();
  common.deleteProperty();
});
