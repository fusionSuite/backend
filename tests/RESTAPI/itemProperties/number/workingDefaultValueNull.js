const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | number type | working set | default value null', function() {

  common.defineValuetype('number');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty(null);
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, null);
    common.deleteItem();
  });

  describe('create item | with number value', function() {
    common.createItemAndCheckOk(true, 604);
    common.deleteItem();
  });

  describe('create item | with null value', function() {
    common.createItemAndCheckOk(true, null);
  });

  describe('update item | with number value', function() {
    common.updateItemAndCheckOk(123);
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
