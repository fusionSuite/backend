const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | datetime type | bad values | create items', function() {

  const dataProvider = [
    {
      description: 'bad datetime format',
      value: '2022-05-06 13:54',
      errorMessage: 'The Value is not valid datetime'
    },
    {
      description: 'integer',
      value: 2022,
      errorMessage: 'The Value is not valid type, The Value is not valid datetime'
    },
    {
      description: 'boolean instead string',
      value: true,
      errorMessage: 'The Value is not valid type, The Value is not valid datetime'
    },
    {
      description: 'miss the 0 in the hour',
      value: '2022-05-06 3:64:05',
      errorMessage: 'The Value is not valid datetime'
    },
    {
      description: 'bad minute',
      value: '2022-05-06 13:64:05',
      errorMessage: 'The Value is not valid datetime'
    }
  ];
 
  common.defineValuetype('datetime');
  common.createType();
  common.createProperty('2022-04-25 21:02:12');
  common.attachPropertyToType();

  dataProvider.forEach(({description, value, errorMessage}) => {
    common.createItemWithError(description, value, errorMessage);
  });

  common.deleteType();
  common.deleteProperty();
});
