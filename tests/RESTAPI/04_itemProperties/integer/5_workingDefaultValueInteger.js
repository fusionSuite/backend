const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | integer type | working set | default value integer', function () {
  describe('prepare', function () {
    it('define the type integer', function (done) {
      common.defineValuetype(done, 'integer');
    });

    it('create a new type integer', function (done) {
      common.createType(done, 'integer');
    });

    it('create the property', function (done) {
      common.createProperty(done, -10);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, -10);
    });

    it('Attach a property to the type integer', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false, -10);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkInteger(done, -10);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: right value', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 69790);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkInteger(done, 69790);
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
      commonCreateItem.checkItemOkInteger(done, null);
    });
  });

  describe('item, update: right value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, -101);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkInteger(done, -101);
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, -10);
    });
  });

  describe('property, update: right value', function () {
    it('update the property', function (done) {
      common.updateProperty(done, 42, 200);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 42);
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test integer', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test integer', function (done) {
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
