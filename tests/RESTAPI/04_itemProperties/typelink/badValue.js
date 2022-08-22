const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | typelink type | bad values | create items', function() {

  const dataProvider = [
    {
      description: 'type id in string',
      value: '2',
      errorMessage: 'The Value is not valid type'
    },
    {
      description: 'boolean',
      value: true,
      errorMessage: 'The Value is not valid type'
    },
    {
      description: 'wrong type id',
      value: 47586,
      errorMessage: 'The Value is an id than does not exist'
    },
    {
      description: 'wrong type id (negative integer)',
      value: -1,
      errorMessage: 'The Value is not valid format'
    },
    {
      description: 'wrong type id (0 value)',
      value: 0,
      errorMessage: 'The Value is an id than does not exist'
    }
  ];

  common.defineValuetype('typelink');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty(1); //with type with id=1 (organization)
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
});
