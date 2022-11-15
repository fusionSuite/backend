const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | text type | working set | default value text', function () {
  describe('prepare', function () {
    it('define the type text', function (done) {
      common.defineValuetype(done, 'text');
    });

    it('create a new type text', function (done) {
      common.createType(done, 'text');
    });

    it('create the property', function (done) {
      common.createProperty(done, 'test texte default\nmultiple lines..');
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 'test texte default\nmultiple lines..');
    });

    it('Attach a property to the type text', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false, 'test texte default\nmultiple lines..');
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
      commonCreateItem.updateItem(done, 'Lorem ipsum dolor sit amet. Est porro eius sed dolorum consequatur et ducimus distinctio qui eius porro. Cum facilis quaerat ut excepturi animi qui vero voluptatum et cupiditate fuga et autem neque qui consectetur vitae qui delectus neque? Aut soluta ratione ad cupiditate maiores et modi rerum ad dignissimos nisi aut debi');
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkString(done, 'Lorem ipsum dolor sit amet. Est porro eius sed dolorum consequatur et ducimus distinctio qui eius porro. Cum facilis quaerat ut excepturi animi qui vero voluptatum et cupiditate fuga et autem neque qui consectetur vitae qui delectus neque? Aut soluta ratione ad cupiditate maiores et modi rerum ad dignissimos nisi aut debi');
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkString(done, 'test texte default\nmultiple lines..');
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
