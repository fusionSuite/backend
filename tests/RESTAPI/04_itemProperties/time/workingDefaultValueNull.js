const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | time type | working set | default value null', function() {

  common.defineValuetype('time');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty(null);
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, null);
    common.deleteItem();
  });

  describe('create item | automaticaly set the current time', function() {
    common.createItemAndCheckOk(true, '');
    common.deleteItem();
  });

  describe('create item | with a rigth time', function() {
    common.createItemAndCheckOk(true, '15:54:24');
  });

  describe('update item | with null', function() {
    common.updateItemAndCheckOk(null);
  });

  describe('update item | automaticaly set the current time', function() {
    common.updateItemAndCheckOk('');
  });

  describe('update item | with a right time', function() {
    common.updateItemAndCheckOk('11:04:00');
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault(null);
  });

  describe('delete and clean', function() {
    // common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });
});
