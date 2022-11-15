const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | string type | working set | default value string', function () {
  describe('prepare', function () {
    it('define the type string', function (done) {
      common.defineValuetype(done, 'string');
    });

    it('create a new type string', function (done) {
      common.createType(done, 'string');
    });

    it('create the property', function (done) {
      common.createProperty(done, 'test string default');
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 'test string default');
    });

    it('Attach a property to the type string', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false, 'test string default');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkString(done, 'test string default');
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: string value', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 'test56');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkString(done, 'test56');
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

  describe('item, update: string value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, 'geronimo');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkString(done, 'geronimo');
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkString(done, 'test string default');
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
