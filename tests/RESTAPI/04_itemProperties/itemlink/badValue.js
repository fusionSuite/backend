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
describe('itemProperties | itemlink type | bad value | create items', function() {

  const dataProvider = [
    {
      description: 'item id in string',
      value: '123',
      errorMessage: 'The Value is not valid type'
    },
    {
      description: 'boolean',
      value: true,
      errorMessage: 'The Value is not valid type'
    },
    {
      description: 'wrong item id',
      value: 47586,
      errorMessage: 'The Value is an id than does not exist'
    },
    {
      description: 'wrong item id (negative integer',
      value: -1,
      errorMessage: 'The Value is not valid format'
    }
  ];

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
    commonReference.createProperty();
    common.attachPropertyToType();
  });

  describe('Multiple test to create items', function() {
    dataProvider.forEach(({description, value, errorMessage}) => {
      common.createItemWithError(description, value, errorMessage);
    });
  });

  describe('delete and clean', function() {
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
