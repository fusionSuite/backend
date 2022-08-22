const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');
const commonReference = require('../commonReference.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | itemlink type | null not allowed | create & update items', function() {

  common.defineValuetype('itemlink');

  describe('create a reference type & item', function() {
    // create first a string type for reference
    // and create an item with this type
    common.defineValuetype('string');
    common.createType();
    common.createProperty('test string');
    common.attachPropertyToType();
    common.createItemAndCheckOk(false, 'test string');
    commonReference.setReference();
  });


  describe('create & update items', function() {
    common.defineValuetype('itemlink');
    common.createType();
    commonReference.createProperty(false);
    common.attachPropertyToType();

    common.createItemWithError('try to define null value', null, 'The Value can\'t be null');

    // create item and try to update it with null value
    commonReference.createItemAndCheckOk(true);
    common.updateItemWithError('use null value', null, 'The Value can\'t be null');
  });

  describe('delete and clean', function() {
    common.deleteItem();
    common.deleteType();
    common.deleteProperty();
  });

  describe('delete the reference item', function() {
    common.defineValuetype('string');
    commonReference.deleteItem(global.referenceId);
    commonReference.deleteType();
    commonReference.deleteProperty();
  });


  describe('create property', function() {

    common.defineValuetype('itemlink');
    common.createType();

    it('create a new property | canbenull: false | define null by default value', function(done) {
      request
      .post('/v1/config/properties')
      .send(
        {
          name: 'Test for itemlink',
          internalname: 'testforitemlink',
          valuetype: 'itemlink',
          regexformat: '',
          listvalues: [],
          unit: '',
          default: null,
          description: 'Test of the type itemlink',
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
