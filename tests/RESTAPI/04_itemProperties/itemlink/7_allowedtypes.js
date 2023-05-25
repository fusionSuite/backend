const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | itemlink type | allowedtypes', function () {
  describe('prepare', function () {
    it('define the type itemlink', function (done) {
      common.defineValuetype(done, 'itemlink');
    });

    it('create a new type itemlink', function (done) {
      common.createType(done, 'itemlink');
    });

    it('create the property', function (done) {
      common.createProperty(done, null, true, [2, 3]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null, [2, 3]);
    });

    it('Attach a property to the type itemlink', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('update property', function () {
    it('update the property', function (done) {
      common.updateProperty(done, null, 200, null, [2, 4]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null, [2, 4]);
    });
  });

  describe('item, create, allowed: item of user type', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 2);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlink(done, 2);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create, not allowed: item "laptop" id1', function () {
    it('create a new item', function (done) {
      common.createItemWithError(done, global.itemId1, 'The Value is an id on type not allowed');
    });
  });

  describe('property, update: allow only laptops (id 3)', function () {
    it('update the property', function (done) {
      common.updateProperty(done, null, 200, null, [3]);
    });
    it('Get the property to check allowedtypes is good', function (done) {
      common.checkProperty(done, null, [3]);
    });
  });

  describe('item, create', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, global.itemId3);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlink(done, global.itemId3);
    });
  });

  describe('item, update, allowed: item of user type', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, global.itemId5);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlink(done, global.itemId5);
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
