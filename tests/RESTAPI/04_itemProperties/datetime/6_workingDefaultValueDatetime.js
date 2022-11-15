const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | datetime type | working set | default value defined datetime', function () {
  describe('prepare', function () {
    it('define the type datetime', function (done) {
      common.defineValuetype(done, 'datetime');
    });

    it('create a new type datetime', function (done) {
      common.createType(done, 'datetime');
    });

    it('create the property', function (done) {
      common.createProperty(done, '2022-04-25 21:02:12');
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, '2022-04-25 21:02:12');
    });

    it('Attach a property to the type datetime', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false, '2022-04-25 21:02:12');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDatetime(done, '2022-04-25 21:02:12');
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: automaticaly set the current datetime', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, '');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDatetime(done, '');
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: rigth datetime', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, '2022-05-06 08:58:37');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDatetime(done, '2022-05-06 08:58:37');
    });
  });

  describe('item, update: null value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, null);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDatetime(done, null);
    });
  });

  describe('item, update:  automatically set the current datetime', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, '');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDatetime(done, '');
    });
  });

  describe('item, update: right datetime', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, '2021-10-26 10:17:11');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDatetime(done, '2021-10-26 10:17:11');
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkDatetime(done, '2022-04-25 21:02:12');
    });
  });

  describe('property, update: right datetime', function () {
    it('update the property - type datetime', function (done) {
      common.updateProperty(done, '2022-06-18 22:34:34', 200);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, '2022-06-18 22:34:34');
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test datetime', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test datetime', function (done) {
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
