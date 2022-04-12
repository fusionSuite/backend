const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | string type | working set | default value string', function() {

  common.defineValuetype('string');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty('test string default');
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, 'test string default');
    common.deleteItem();
  });

  describe('create item | with string value', function() {
    common.createItemAndCheckOk(true, 'test56');
    common.deleteItem();
  });

  describe('create item | with null value', function() {
    common.createItemAndCheckOk(true, null);
  });

  describe('update item | with string value', function() {
    common.updateItemAndCheckOk('geronimo');
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault('test string default');
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });
});
