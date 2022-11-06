const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | password type | working set | default value password', function() {

  common.defineValuetype('password');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty('password number 1');
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, 'password number 1');
    common.deleteItem();
  });

  describe('create item | with password value', function() {
    common.createItemAndCheckOk(true, 'password number 2');
    common.deleteItem();
  });

  describe('create item | with null value', function() {
    common.createItemAndCheckOk(true, null);
  });

  describe('update item | with password value', function() {
    common.updateItemAndCheckOk('password number 3');
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault('password number 1');
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });
});
