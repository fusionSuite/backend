const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | date type | working set | default value defined date', function () {
  describe('prepare', function () {
    it('define the type date', function (done) {
      common.defineValuetype(done, 'date');
    });

    it('create a new type date', function (done) {
      common.createType(done, 'date');
    });

    it('create the property', function (done) {
      common.createProperty(done, '2022-04-25');
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, '2022-04-25');
    });

    it('Attach a property to the type date', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: default date', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false, '2022-04-25');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDate(done, '2022-04-25');
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: rigth date', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, '2022-05-06');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDate(done, '2022-05-06');
    });
  });

  describe('item, update: null value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDate(done, null);
    });
  });

  describe('item, update: automatically set the current date', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, '');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDate(done, '');
    });
  });

  describe('item, update: right date', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, '2021-10-26');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDate(done, '2021-10-26');
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDate(done, '2022-04-25');
    });
  });

  describe('property, update: right date', function () {
    it('update the property - type date', function (done) {
      common.updateProperty(done, '2022-06-19', 200);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, '2022-06-19');
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test date', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test date', function (done) {
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
