const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | typelinks type | working set | default value', function () {
  describe('prepare', function () {
    it('define the type typelinks', function (done) {
      common.defineValuetype(done, 'typelinks');
    });

    it('create a new type typelinks', function (done) {
      common.createType(done, 'typelinks');
    });

    it('create the property', function (done) {
      common.createProperty(done, [1, 2]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, [1, 2]);
    });

    it('Attach a property to the type typelinks', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false, [1, 2]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [1, 2]);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: type id', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, [2]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [2]);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: multiple type id', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, [1, 3, 4]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [1, 3, 4]);
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
      commonCreateItem.checkItemOkTypelinks(done, null);
    });
  });

  describe('item, update: types id [1, 2]', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, [1, 2]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [1, 2]);
    });
  });

  describe('item, update: anothers types id', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, [4, 5, 6]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [4, 5, 6]);
    });
  });

  describe('item, update: some id, some delete, and some same types id', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, [5, 1]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [5, 1]);
    });
  });

  describe('item, update: return to null value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, null);
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [1, 2]);
    });
  });

  describe('item, update: types id [2, 4]', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, [2, 4]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [2, 4]);
    });
  });

  describe('item, special: add only one typelink', function () {
    it('Update the item with 3 value', function (done) {
      common.addLinkDedicatedEndpoint(done, 3);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [2, 4, 3]);
    });
  });

  describe('item, special: delete only one typelink', function () {
    it('Update the item with 1 value', function (done) {
      common.deleteLinkDedicatedEndpoint(done, 4);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [2, 3]);
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test typelinks', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test typelinks', function (done) {
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
