const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | time type | bad values | create items', function() {

  const dataProvider = [
    {
      description: 'text not time',
      value: 'lala la',
      errorMessage: 'The Value is not valid time'
    },
    {
      description: 'bad time format',
      value: '23:62:04',
      errorMessage: 'The Value is not valid time'
    },
    {
      description: 'miss a 0 in the time',
      value: '3:42:04',
      errorMessage: 'The Value is not valid time'
    }
  ];

  common.defineValuetype('time');
  common.createType();
  common.createProperty('07:04:21');
  common.attachPropertyToType();

  dataProvider.forEach(({description, value, errorMessage}) => {
    common.createItemWithError(description, value, errorMessage);
  });

  common.deleteType();
  common.deleteProperty();
});
