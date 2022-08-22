const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | boolean type | working set | default value true', function() {

  common.defineValuetype('boolean');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty(true);
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, true);
    common.deleteItem();
  });

  describe('create item | with false value', function() {
    common.createItemAndCheckOk(true, false);
    common.deleteItem();
  });

  describe('create item | with null value', function() {
    common.createItemAndCheckOk(true, null);
  });

  describe('update item | with false value', function() {
    common.updateItemAndCheckOk(false);
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault(true);
  });

  describe('update the property', function() {
    common.updateProperty(false, 200);
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });
});
