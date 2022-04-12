const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | time type | working set | default value defined time', function() {

  common.defineValuetype('time');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty('07:04:21');
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, '07:04:21');
    common.deleteItem();
  });

  describe('create item | automaticaly set the current time', function() {
    common.createItemAndCheckOk(true, '');
    common.deleteItem();
  });

  describe('create item | with a rigth time', function() {
    common.createItemAndCheckOk(true, '22:03:46');
  });

  describe('update item | with null', function() {
    common.updateItemAndCheckOk(null);
  });

  describe('update item | automaticaly set the current time', function() {
    common.updateItemAndCheckOk('');
  });

  describe('update item | with a right time', function() {
    common.updateItemAndCheckOk('10:54:23');
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault('07:04:21');
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });
});
