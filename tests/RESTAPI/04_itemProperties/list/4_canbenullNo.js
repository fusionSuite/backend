const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | list type | null not allowed | create & update items', function () {
  describe('prepare', function () {
    it('define the type list', function (done) {
      common.defineValuetype(done, 'list');
    });

    it('create a new type list', function (done) {
      common.createType(done, 'list');
    });

    it('create a new property - type list', function (done) {
      request
        .post('/v1/config/properties')
        .send(
          {
            name: 'Test for list',
            internalname: 'testforlist',
            valuetype: 'list',
            regexformat: '',
            listvalues: ['list1', 'list2'],
            unit: '',
            default: 'list2',
            description: 'Test of the type list',
            canbenull: false,
          })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.propertyCount(response.body, 1));

          assert(is.integer(response.body.id));
          assert(validator.matches('' + response.body.id, /^\d+$/));
          global.propertyvaluesid = response.body.id;
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('Attach a property to the type list', function (done) {
      common.attachPropertyToType(done);
    });

    it('Get the list of ids', function (done) {
      common.getListIds(done);
    });
  });

  describe('item, create: null value => error', function () {
    it('try create a new item but return error (try to define null value)', function (done) {
      common.createItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('item, update: null value => error', function () {
    // create item and try to update it with null value
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 'list1');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkList(done, 'list1');
    });

    it('update item', function (done) {
      common.updateItemWithError(done, null, 'The Value can\'t be null');
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test list', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test list', function (done) {
      common.deleteType(done);
    });

    it('Soft delete the property', function (done) {
      common.deleteProperty(done);
    });

    it('Hard delete the property', function (done) {
      common.deleteProperty(done);
    });
  });

  describe('create property', function () {
    it('create a new type list', function (done) {
      common.createType(done, 'list');
    });

    it('create a new property | canbenull: false | define null by default value', function (done) {
      request
        .post('/v1/config/properties')
        .send(
          {
            name: 'Test for list',
            internalname: 'testforlist',
            valuetype: 'list',
            regexformat: '',
            listvalues: ['list1', 'list2'],
            unit: '',
            default: null,
            description: 'Test of the type list',
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
    it('Soft delete the type: test number', function (done) {
      common.deleteType(done);
    });
    it('Hard delete the type: test number', function (done) {
      common.deleteType(done);
    });
  });
});
