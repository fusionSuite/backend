const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | integer type | bad values | create items', function() {

  const dataProvider = [
    {
      description: 'integer into string',
      value: '10',
      errorMessage: 'The Value is not valid type'
    },
    {
      description: 'float',
      value: 10.1,
      errorMessage: 'The Value is not valid type, The Value must be integer'
    },
    {
      description: 'boolean',
      value: true,
      errorMessage: 'The Value is not valid type'
    }
  ];
  
  common.defineValuetype('integer');
  common.createType();
  common.createProperty(-10);
  common.attachPropertyToType();

  dataProvider.forEach(({description, value, errorMessage}) => {
    common.createItemWithError(description, value, errorMessage);
  });

  common.deleteType();
  common.deleteProperty();
});
