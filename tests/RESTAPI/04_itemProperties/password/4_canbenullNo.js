const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | password type | null not allowed | create & update items', function () {
  describe('prepare', function () {
    it('define the type password', function (done) {
      common.defineValuetype(done, 'password');
    });

    it('create a new type password', function (done) {
      common.createType(done, 'password');
    });

    it('create the property', function (done) {
      common.createProperty(done, 'my default password', false);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 'my default password');
    });

    it('Attach a property to the type password', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: null value', function () {
    it('try create a new item but return error (try to define null value)', function (done) {
      common.createItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('item, update: null value', function () {
    // create item and try to update it with null value
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 'test');
    });

    it('Get the item to check value is null when get all items of the type', function (done) {
      commonCreateItem.checkItemsOkPassword(done, null);
    });

    it('Get the item to check value is `test` when get only the item', function (done) {
      commonCreateItem.checkItemOkPassword(done, 'test');
    });

    it('try udpate item with null value => error', function (done) {
      common.updateItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('property, create: can\'t be null, set null => error  ', function () {
    it('create a new property | canbenull: false | define null by default value', function (done) {
      request
        .post('/v1/config/properties')
        .send(
          {
            name: 'Test for password',
            internalname: 'testforpassword',
            valuetype: 'password',
            regexformat: '',
            listvalues: [],
            unit: '',
            default: null,
            description: 'Test of the type password',
            canbenull: false,
          })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(400)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.propertyCount(response.body, 2));
          assert(validator.equals(response.body.status, 'error'));
          assert(validator.equals(response.body.message, 'The Default can\'t be null'));
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test password', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test password', function (done) {
      common.deleteType(done);
    });

    it('Soft delete the property', function (done) {
      common.deleteProperty(done);
    });

    it('Hard delete the property', function (done) {
      common.deleteProperty(done);
    });
  });
});
