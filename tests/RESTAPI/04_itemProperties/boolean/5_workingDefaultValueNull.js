const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | boolean type | working set | default value null', function () {
  describe('prepare', function () {
    it('define the type boolean', function (done) {
      common.defineValuetype(done, 'boolean');
    });

    it('create a new type', function (done) {
      common.createType(done, 'boolean');
    });

    it('create the property', function (done) {
      common.createProperty(done, null);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null);
    });

    it('Attach a property to the type', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item without the property', function (done) {
      commonCreateItem.createItem(done, false, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkBoolean(done, null);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: false value', function () {
    it('create a new item with false value', function (done) {
      commonCreateItem.createItem(done, true, false);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkBoolean(done, false);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: null value', function () {
    it('create a new item with null value', function (done) {
      commonCreateItem.createItem(done, true, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkBoolean(done, null);
    });
  });

  describe('item, update: true value', function () {
    it('update item with true value', function (done) {
      commonCreateItem.updateItem(done, true);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkBoolean(done, true);
    });
  });

  describe('item, update: default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkBoolean(done, null);
    });
  });

  describe('property, update: false value', function () {
    it('update the property to false', function (done) {
      common.updateProperty(done, false, 200);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, false);
    });
  });

  describe('property, update: null value', function () {
    it('update the property to null', function (done) {
      common.updateProperty(done, null, 200);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null);
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test boolean', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test boolean', function (done) {
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
