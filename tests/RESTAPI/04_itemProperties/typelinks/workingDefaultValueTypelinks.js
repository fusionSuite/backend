const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | typelinks type | working set | default value', function() {

  common.defineValuetype('typelinks');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty([1, 2]);
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, [1, 2]);
    common.deleteItem();
  });

  describe('create item | with type id', function() {
    common.createItemAndCheckOk(true, [2]);
    common.deleteItem();
  });

  describe('create item | with multiple type id', function() {
    common.createItemAndCheckOk(true, [1, 3, 4]);
    common.deleteItem();
  });

  describe('create item | with null value', function() {
    common.createItemAndCheckOk(true, null);
  });

  describe('update item | with types id', function() {
    common.updateItemAndCheckOk([1,2]);
  });

  describe('update item | with anothers types id', function() {
    common.updateItemAndCheckOk([4,5,6]);
  });

  describe('update item | with some ad, some delete, and some same types id', function() {
    common.updateItemAndCheckOk([5,1]);
  });

  describe('update item | return to null value', function () {
    common.updateItemAndCheckOk(null);
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault([1,2]);
  });

  describe('update item | with types id', function() {
    common.updateItemAndCheckOk([2,4]);
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault([2,1]);
  });

  describe('special item | add only one typelink', function () {
    common.addLinkDedicatedEndpointAndCheckOk(3, [2,1,3]);
  });

  describe('special item | delete only one typelink', function () {
    common.deleteLinkDedicatedEndpointAndCheckOk(1, [2,3]);
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });

});
