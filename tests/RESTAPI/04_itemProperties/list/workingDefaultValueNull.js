const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | list type | working set | default value null', function() {

  common.defineValuetype('list');
  describe('create the type and the property', function() {
    common.createType();

    it('create a new property - type list', function(done) {
      request
      .post('/v1/config/properties')
      .send(
        {
          name: 'Test for list',
          internalname: 'testforlist',
          valuetype: 'list',
          regexformat: '',
          listvalues: ['list1','list2'],
          unit: '',
          default: null,
          description: 'Test of the type list'
        })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyvaluesid = response.body.id;
      })
      .end(function(err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
    });
    common.attachPropertyToType();
    common.getListIds();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, null);
    common.deleteItem();
  });

  describe('create item | with list1', function() {
    common.createItemAndCheckOk(true, 'list1');
    common.deleteItem();
  });

  describe('create item | with null value', function() {
    common.createItemAndCheckOk(true, null);
  });

  describe('update item | with list value', function() {
    common.updateItemAndCheckOk('list1');
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault(null);
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });
});
