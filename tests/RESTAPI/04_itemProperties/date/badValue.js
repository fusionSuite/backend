const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | date type | bad values | create items', function() {

  const dataProvider = [
    {
      description: 'date inversed',
      value: '06-05-2022',
      errorMessage: 'The Value is not valid date'
    },
    {
      description: 'integer',
      value: 2022,
      errorMessage: 'The Value is not valid type, The Value is not valid date'
    },
    {
      description: 'wrong month',
      value: '2022-15-13',
      errorMessage: 'The Value is not valid date'
    },
    {
      description: 'miss the 0 in month number',
      value: '2022-5-13',
      errorMessage: 'The Value is not valid date'
    },
    {
      description: 'text string',
      value: 'sometext',
      errorMessage: 'The Value is not valid date'
    }
  ];

  common.defineValuetype('date');
  common.createType();
  common.createProperty('2022-04-25');
  common.attachPropertyToType();

  dataProvider.forEach(({description, value, errorMessage}) => {
    common.createItemWithError(description, value, errorMessage);
  });

  common.deleteType();
  common.deleteProperty();
});
