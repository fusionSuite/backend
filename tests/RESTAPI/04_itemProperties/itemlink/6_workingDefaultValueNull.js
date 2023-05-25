const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | itemlink type | working set | default value null', function () {
  describe('prepare', function () {
    it('define the type itemlink', function (done) {
      common.defineValuetype(done, 'itemlink');
    });

    it('create a new type itemlink', function (done) {
      common.createType(done, 'itemlink');
    });

    it('create the property', function (done) {
      common.createProperty(done, null, true, [3]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null, [3]);
    });

    it('Attach a property to the type itemlink', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkString(done, null);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: item id1', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, global.itemId1);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlink(done, global.itemId1);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create:  null value', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlink(done, null);
    });
  });

  describe('item, update: item id2', function () {
    it('update item with false value', function (done) {
      commonCreateItem.updateItem(done, global.itemId2);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlink(done, global.itemId2);
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null, [3]);
    });
  });

  describe('property, update: item id4', function () {
    it('update the property', function (done) {
      common.updateProperty(done, global.itemId4, 200);
    });
    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, global.itemId4, [3]);
    });
  });

  describe('property, update: null value', function () {
    it('update the property', function (done) {
      common.updateProperty(done, null, 200);
    });
    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null, [3]);
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test itemlink', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test itemlink', function (done) {
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
