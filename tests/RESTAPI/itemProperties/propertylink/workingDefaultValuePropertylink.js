const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | propertylink type | working set | default value', function() {

  common.defineValuetype('propertylink');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty(1);
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, 1);
    common.deleteItem();
  });

  describe('create item | with property id', function() {
    common.createItemAndCheckOk(true, 2);
    common.deleteItem();
  });

  describe('create item | with null value', function() {
    common.createItemAndCheckOk(true, null);
  });

  describe('update item | with property id', function() {
    common.updateItemAndCheckOk(1);
  });

  describe('update item | return to null value', function () {
    common.updateItemAndCheckOk(null);
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault(1);
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });

});
