const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | time type | working set | default value null', function () {
  describe('prepare', function () {
    it('define the type time', function (done) {
      common.defineValuetype(done, 'time');
    });

    it('create a new type time', function (done) {
      common.createType(done, 'time');
    });

    it('create the property', function (done) {
      common.createProperty(done, null);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null);
    });

    it('Attach a property to the type time', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTime(done, null);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: automatically set the current time', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, '');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTime(done, '');
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: rigth time', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, '15:54:24');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTime(done, '15:54:24');
    });
  });

  describe('item, update: null value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTime(done, null);
    });
  });

  describe('item, update: automatically set the current time', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, '');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTime(done, '');
    });
  });

  describe('item, update: right time', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, '11:04:00');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTime(done, '11:04:00');
    });
  });

  describe('update item | return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTime(done, null);
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test string', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test string', function (done) {
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
