const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | list type | working set | default value null', function () {
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
            default: null,
            description: 'Test of the type list',
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

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkList(done, null);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: list1', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 'list1');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkList(done, 'list1');
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: null value', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkList(done, null);
    });
  });

  describe('item, update: list1', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, 'list1');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkList(done, 'list1');
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkList(done, null);
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
});
