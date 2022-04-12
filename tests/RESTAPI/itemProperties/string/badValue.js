const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | string type | bad values | create items', function() {

  const dataProvider = [
    {
      description: 'number',
      value: 42,
      errorMessage: 'The Value is not valid type'
    },
    {
      description: 'too long string',
      value: 'Lorem ipsum dolor sit amet. Est porro eius sed dolorum consequatur et ducimus distinctio qui eius porro. Cum facilis quaerat ut excepturi animi qui vero voluptatum et cupiditate fuga et autem neque qui consectetur vitae qui delectus neque? Aut soluta ratione ad cupiditate maiores et modi rerum ad dignissimos nisi aut debi',
      errorMessage: 'The Value is too long, max 255 chars'
    }
  ];

  common.defineValuetype('string');
  common.createType();
  common.createProperty('test string default');
  common.attachPropertyToType();

  dataProvider.forEach(({description, value, errorMessage}) => {
    common.createItemWithError(description, value, errorMessage);
  });

  common.deleteType();
  common.deleteProperty();
});
