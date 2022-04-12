const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | date type | working set | default value defined date', function() {

  common.defineValuetype('date');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty('2022-04-25');
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, '2022-04-25');
    common.deleteItem();
  });

  describe('create item | automaticaly set the current date', function() {
    common.createItemAndCheckOk(true, '');
    common.deleteItem();
  });

  describe('create item | with a rigth date', function() {
    common.createItemAndCheckOk(true, '2022-05-06');
  });

  describe('update item | with null', function() {
    common.updateItemAndCheckOk(null);
  });

  describe('update item | automaticaly set the current date', function() {
    common.updateItemAndCheckOk('');
  });

  describe('update item | with a right date', function() {
    common.updateItemAndCheckOk('2021-10-26');
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault('2022-04-25');
  });

  describe('update the property', function() {
    common.updateProperty('2022-06-19', 200);
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });
});
