const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('Test itemProperties | list type | bad values | create items', function() {

  const dataProvider = [
    {
      description: 'number',
      value: 5467,
      errorMessage: 'The Value is an id than does not exist'
    },
    {
      description: 'string',
      value: 'list1',
      errorMessage: 'The Value is not valid type, The Value is not valid format'
    }
  ];

  common.defineValuetype('list');
  describe('create the type and the property', function() {
    common.createType();

    it('create a new property - type list', function(done) {
      request
      .post('/v1/config/properties')
      .send(
        {
          name: 'Test for list',
          internalname: 'testforlist',
          valuetype: 'list',
          regexformat: '',
          listvalues: ['list1','list2'],
          unit: '',
          default: 'list2',
          description: 'Test of the type list'
        })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyvaluesid = response.body.id;
      })
      .end(function(err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
    });
    common.attachPropertyToType();
    common.getListIds();
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
