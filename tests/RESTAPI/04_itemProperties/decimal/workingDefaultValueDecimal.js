const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | decimal type | working set | default value decimal', function() {

  common.defineValuetype('decimal');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty(3.1416);
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, 3.1416);
    common.deleteItem();
  });

  describe('create item | with decimal value', function() {
    common.createItemAndCheckOk(true, 30.1);
    common.deleteItem();
  });

  describe('create item | with null value', function() {
    common.createItemAndCheckOk(true, null);
  });

  describe('update item | with decimal value', function() {
    common.updateItemAndCheckOk(50.99);
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault(3.1416);
  });

  describe('update the property', function() {
    common.updateProperty(3.4, 200);
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });
});
