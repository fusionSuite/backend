const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | number type | working set | default value number', function () {
  describe('prepare', function () {
    it('define the type number', function (done) {
      common.defineValuetype(done, 'number');
    });

    it('create a new type number', function (done) {
      common.createType(done, 'number');
    });

    it('create the property', function (done) {
      common.createProperty(done, 5);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 5);
    });

    it('Attach a property to the type number', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkNumber(done, 5);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: number value', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 69790);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkNumber(done, 69790);
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
      commonCreateItem.checkItemOkNumber(done, null);
    });
  });

  describe('item, update: number value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, 35);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkNumber(done, 35);
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkNumber(done, 5);
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test number', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test number', function (done) {
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
