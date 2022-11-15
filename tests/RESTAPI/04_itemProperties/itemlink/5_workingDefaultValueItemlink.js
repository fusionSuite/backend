const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | itemlink type | working set | default value', function () {
  describe('prepare', function () {
    it('define the type itemlink', function (done) {
      common.defineValuetype(done, 'itemlink');
    });

    it('create a new type itemlink', function (done) {
      common.createType(done, 'itemlink');
    });

    it('create a property, item id1', function (done) {
      common.createProperty(done, global.itemId1);
    });

    it('Attach a property to the type itemlink', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false);
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

  describe('item, create: item id2', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, global.itemId2);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlink(done, global.itemId2);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('create item | with null value', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, null);
    });
    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlink(done, null);
    });
  });

  describe('item, update: item id3', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, global.itemId3);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlink(done, global.itemId3);
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, global.itemId1);
    });
  });

  describe('property, update: item id4', function () {
    it('update the property ', function (done) {
      common.updateProperty(done, global.itemId4, 200);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, global.itemId4);
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
