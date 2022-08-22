const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | boolean type | bad values | create items', function() {

  const dataProvider = [
    {
      description: 'boolean in string',
      value: 'false',
      errorMessage: 'The Value is not valid type'
    }
  ];

  common.defineValuetype('boolean');
  common.createType();
  common.createProperty(true);
  common.attachPropertyToType();

  dataProvider.forEach(({description, value, errorMessage}) => {
    common.createItemWithError(description, value, errorMessage);
  });

  common.deleteType();
  common.deleteProperty();
});
