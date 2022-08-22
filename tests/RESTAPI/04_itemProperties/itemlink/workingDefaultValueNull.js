const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');
const commonReference = require('../commonReference.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | itemlink type | working set | default value null', function() {

  common.defineValuetype('itemlink');

  describe('create a reference type & item', function() {
    // create first a string type for reference
    // and create an item with this type
    common.defineValuetype('string');
    common.createType();
    common.createProperty('test string');
    common.attachPropertyToType();
    common.createItemAndCheckOk(false, 'test string');
    commonReference.setReference();
  });

  describe('create the type and the property', function() {
    common.defineValuetype('itemlink');
    common.createType();
    common.createProperty(null);
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, null);
    common.deleteItem();
  });

  describe('create item | with item id', function() {
    commonReference.createItemAndCheckOk(true);
    common.deleteItem();
  });

  describe('create item | with null value', function() {
    common.createItemAndCheckOk(true, null);
  });

  describe('update item | with item id', function() {
    commonReference.updateItemAndCheckOk();
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault(null);
  });

  // describe('update the property to false', function() {
  //   common.updateProperty(false, 200);
  // });

  // describe('update the property to null', function() {
  //   common.updateProperty(null, 200);
  // });

  describe('delete and clean', function() {
    // common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });

  describe('delete the reference item', function() {
    common.defineValuetype('string');
    commonReference.deleteItem(global.referenceId);
    commonReference.deleteType();
    commonReference.deleteProperty();
  });

});
