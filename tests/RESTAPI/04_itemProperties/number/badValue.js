const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | number type | bad values | create items', function() {

  const dataProvider = [
    {
      description: 'string',
      value: '10',
      errorMessage: 'The Value is not valid type'
    },
    {
      description: 'negative integer',
      value: -2,
      errorMessage: 'The Value is not valid format'
    }
  ];

  common.defineValuetype('number');
  common.createType();
  common.createProperty(5);
  common.attachPropertyToType();

  dataProvider.forEach(({description, value, errorMessage}) => {
    common.createItemWithError(description, value, errorMessage);
  });

  common.deleteType();
  common.deleteProperty();
});
