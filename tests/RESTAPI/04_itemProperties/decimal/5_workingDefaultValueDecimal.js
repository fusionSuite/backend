const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | decimal type | working set | default value decimal', function () {
  describe('prepare', function () {
    it('define the type decimal', function (done) {
      common.defineValuetype(done, 'decimal');
    });

    it('create a new type decimal', function (done) {
      common.createType(done, 'decimal');
    });

    it('create the property', function (done) {
      common.createProperty(done, 3.1416);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 3.1416);
    });

    it('Attach a property to the type decimal', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create:  no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false, 3.1416);
    });
    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDecimal(done, 3.1416);
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
      commonCreateItem.createItem(done, true, 30.1);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDecimal(done, 30.1);
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
      commonCreateItem.checkItemOkDecimal(done, null);
    });
  });

  describe('item, update: right value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, 50.99);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDecimal(done, 50.99);
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 3.1416);
    });
  });

  describe('property, update: right value', function () {
    it('update the property - type decimal', function (done) {
      common.updateProperty(done, 3.4, 200);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 3.4);
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test decimal', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test decimal', function (done) {
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
