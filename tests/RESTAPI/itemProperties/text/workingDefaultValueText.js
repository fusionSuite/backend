const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | text type | working set | default value text', function() {

  common.defineValuetype('text');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty("test texte default\nmultiple lines..");
    common.attachPropertyToType();
  });

  describe('create item | without the property', function() {
    common.createItemAndCheckOk(false, "test texte default\nmultiple lines..");
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
    common.updateItemAndCheckOk('Lorem ipsum dolor sit amet. Est porro eius sed dolorum consequatur et ducimus distinctio qui eius porro. Cum facilis quaerat ut excepturi animi qui vero voluptatum et cupiditate fuga et autem neque qui consectetur vitae qui delectus neque? Aut soluta ratione ad cupiditate maiores et modi rerum ad dignissimos nisi aut debi');
  });

  describe('update item | return to default value', function () {
    common.updateItemToDefault("test texte default\nmultiple lines..");
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });
});
