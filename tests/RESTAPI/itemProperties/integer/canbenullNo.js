const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | integer type | null not allowed | create & update items', function() {

  describe('create & update items', function() {
    common.defineValuetype('integer');
    common.createType();
    common.createProperty(-10, false);
    common.attachPropertyToType();

    common.createItemWithError('try to define null value', null, 'The Value can\'t be null');

    // create item and try to update it with null value
    common.createItemAndCheckOk(true, 50);
    common.updateItemWithError('use null value', null, 'The Value can\'t be null');
    common.deleteItem();

    common.deleteType();
    common.deleteProperty();
  });

  describe('create property', function() {

    common.defineValuetype('integer');
    common.createType();

    it('create a new property | canbenull: false | define null by default value', function(done) {
      request
      .post('/v1/config/properties')
      .send(
        {
          name: 'Test for integer',
          internalname: 'testforinteger',
          valuetype: 'integer',
          regexformat: '',
          listvalues: [],
          unit: '',
          default: null,
          description: 'Test of the type integer',
          canbenull: false
        })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(400)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        assert(validator.equals(response.body.message, 'The Default can\'t be null'));
      })
      .end(function(err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
    });
    common.deleteType();
  });
});
