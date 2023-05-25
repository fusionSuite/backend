const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties: itemlinks type - working set | default value', function () {
  describe('prepare', function () {
    it('define the type itemlinks', function (done) {
      common.defineValuetype(done, 'itemlinks');
    });

    it('create a new type itemlinks', function (done) {
      common.createType(done, 'itemlinks');
    });

    it('create a new property - type itemlinks', function (done) {
      common.createProperty(done, [global.itemId1, global.itemId2], true, [3]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, [global.itemId1, global.itemId2], [3]);
    });

    it('Attach a property to the type itemlinks', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, [global.itemId1, global.itemId2]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId1, global.itemId2]);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: [item id2]', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, [global.itemId2]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId2]);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: multiple item id', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, [global.itemId1, global.itemId3, global.itemId4]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId1, global.itemId3, global.itemId4]);
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
      commonCreateItem.checkItemOkItemlinks(done, null);
    });
  });

  describe('item, update: multiple items id [itemId1, itemId2]', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, [global.itemId1, global.itemId2]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId1, global.itemId2]);
    });
  });

  describe('item, update: anothers multiple items id', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, [global.itemId4, global.itemId5, global.itemId6]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId4, global.itemId5, global.itemId6]);
    });
  });

  describe('item, update: some id, some delete, and some same items id', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, [global.itemId5, global.itemId1]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId5, global.itemId1]);
    });
  });

  describe('item, update: return to null value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, null);
    });
  });

  describe('item, update: return to default value [itemId1, itemId2]', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId1, global.itemId2]);
    });
  });

  describe('item, update: multiple items id [itemId2, itemId4]', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, [global.itemId2, global.itemId4]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId2, global.itemId4]);
    });
  });

  describe('item, update: return to default value [itemId2, itemId1]', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId2, global.itemId1]);
    });
  });

  describe('special item: add only one itemlink', function () {
    it('Update the item with 2 value', function (done) {
      common.addLinkDedicatedEndpoint(done, global.itemId5);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId2, global.itemId1, global.itemId5]);
    });
  });

  describe('special item: delete only one itemlink', function () {
    it('Update the item with 1 value', function (done) {
      common.deleteLinkDedicatedEndpoint(done, global.itemId1);
    });
    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId2, global.itemId5]);
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test itemlinks', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test itemlinks', function (done) {
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
