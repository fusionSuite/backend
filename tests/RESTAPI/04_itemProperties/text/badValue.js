const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | text type | bad value | create items', function() {

  const dataProvider = [
    {
      description: 'integer',
      value: 10,
      errorMessage: 'The Value is not valid type'
    }
  ];

  common.defineValuetype('text');
  common.createType();
  common.createProperty("test texte default\nmultiple lines..");
  common.attachPropertyToType();

  dataProvider.forEach(({description, value, errorMessage}) => {
    common.createItemWithError(description, value, errorMessage);
  });

  common.deleteType();
  common.deleteProperty();
});
