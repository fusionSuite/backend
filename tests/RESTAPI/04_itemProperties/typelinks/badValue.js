const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | typelinks type | bad values | create items', function() {

  const dataProvider = [
    {
      description: 'type id not in array',
      value: 2,
      errorMessage: 'The Value must be array'
    },
    {
      description: 'type id in string',
      value: '2',
      errorMessage: 'The Value must be array'
    },
    {
      description: 'types id (style array) in string',
      value: '[2,3]',
      errorMessage: 'The Value must be array'
    },
    {
      description: 'type id not exists',
      value: [267584],
      errorMessage: 'The Value is an id than does not exist'
    },
    {
      description: 'types id with 1 of the 2 not exists',
      value: [3, 267584],
      errorMessage: 'The Value is an id than does not exist'
    }
  ];

  common.defineValuetype('typelinks');
  describe('create the type and the property', function() {
    common.createType();
    common.createProperty([1, 2]);
    common.attachPropertyToType();
  });

  describe('Multiple test to create items', function() {
    dataProvider.forEach(({description, value, errorMessage}) => {
      common.createItemWithError(description, value, errorMessage);
    });
  });

  describe('create item | with types id', function() {
    common.createItemAndCheckOk(true, [1, 2]);
  });

  describe('try add typelink but have error', function () {
    common.addLinkDedicatedEndpointWithError('null value', null, 'The Value is required');
    common.addLinkDedicatedEndpointWithError('empty string value', '', 'The Value is required');
    common.addLinkDedicatedEndpointWithError('if not exists', 54964, 'The Value is an id than does not exist');
    common.addLinkDedicatedEndpointWithError('0 value', 0, 'The Value is an id than does not exist');
    common.addLinkDedicatedEndpointWithError('id has string', '5', 'The Value is not valid type');
    common.addLinkDedicatedEndpointWithError('negative id', -3, 'The Value is not valid format');
  });

  describe('try delete typelink but have error', function () {
    common.deleteLinkDedicatedEndpointWithError('null value', null, 405, 'Method not allowed. Must be one of: OPTIONS');
    common.deleteLinkDedicatedEndpointWithError('null value', 0, 400, 'The typelink is an id than does not exist');
    common.deleteLinkDedicatedEndpointWithError('null value', -4, 405, 'Method not allowed. Must be one of: OPTIONS');
    common.deleteLinkDedicatedEndpointWithError('null value', 598659, 400, 'The typelink is an id than does not exist');
  });

  describe('delete and clean', function() {
    common.deleteType();
    common.deleteProperty();
  });

});
