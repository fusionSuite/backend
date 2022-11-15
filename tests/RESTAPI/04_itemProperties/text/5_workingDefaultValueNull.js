const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | text type | working set | default value null', function () {
  describe('prepare', function () {
    it('define the type text', function (done) {
      common.defineValuetype(done, 'text');
    });

    it('create a new type text', function (done) {
      common.createType(done, 'text');
    });

    it('create the property', function (done) {
      common.createProperty(done, null);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null);
    });

    it('Attach a property to the type text', function (done) {
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

  describe('item, create: text value', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 'test texte default\nmultiple lines..');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkString(done, 'test texte default\nmultiple lines..');
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
      commonCreateItem.checkItemOkString(done, null);
    });
  });

  describe('item, update: text value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, 'my text');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkString(done, 'my text');
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkString(done, null);
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test text', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test text', function (done) {
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
