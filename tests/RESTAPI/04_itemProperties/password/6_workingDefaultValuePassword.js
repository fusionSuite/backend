const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | password type | working set | default value password', function () {
  describe('prepare', function () {
    it('define the type password', function (done) {
      common.defineValuetype(done, 'password');
    });

    it('create a new type password', function (done) {
      common.createType(done, 'password');
    });

    it('create the property', function (done) {
      common.createProperty(done, 'password number 1');
    });

    it('Get the property to check password is good', function (done) {
      common.checkProperty(done, 'password number 1');
    });

    it('Check if default password is right encrypted into database', function (done) {
      commonCreateItem.checkDefaultPropertyPasswordEncryptedDatabase(done, 'password number 1');
    });

    it('Attach a property to the type password', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: no property', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, false);
    });

    it('Get the item from all items of type to check password is good', function (done) {
      commonCreateItem.checkItemsOkPassword(done, null);
    });

    it('Get the item to check value is `password number 1` when get only the item', function (done) {
      commonCreateItem.checkItemOkPassword(done, 'password number 1');
    });

    it('Check if password is right encrypted into database', function (done) {
      commonCreateItem.checkItemOkPasswordEncryptedDatabase(done, 'password number 1');
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create: password value', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 'test password 2');
    });

    it('Get the item from all items of type to check password is good', function (done) {
      commonCreateItem.checkItemsOkPassword(done, null);
    });

    it('Get the item to check value is `test password 2` when get only the item', function (done) {
      commonCreateItem.checkItemOkPassword(done, 'test password 2');
    });

    it('Check if password is right encrypted into database', function (done) {
      commonCreateItem.checkItemOkPasswordEncryptedDatabase(done, 'test password 2');
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

    it('Get the item from all items of type to check password is good', function (done) {
      commonCreateItem.checkItemsOkPassword(done, null);
    });

    it('Get the item to check value is null when get only the item', function (done) {
      commonCreateItem.checkItemOkPassword(done, null);
    });
  });

  describe('item, update: passord value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, 'passwd 45');
    });

    it('Get the item from all items of type to check password is good', function (done) {
      commonCreateItem.checkItemsOkPassword(done, null);
    });

    it('Get the item to check value is `passwd 45` when get only the item', function (done) {
      commonCreateItem.checkItemOkPassword(done, 'passwd 45');
    });

    it('Check if password is right encrypted into database', function (done) {
      commonCreateItem.checkItemOkPasswordEncryptedDatabase(done, 'passwd 45');
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item from all items of type to check value is good', function (done) {
      commonCreateItem.checkItemsOkPassword(done, null);
    });

    it('Get the item to check value is `password number 1` when get only the item', function (done) {
      commonCreateItem.checkItemOkPassword(done, 'password number 1');
    });

    it('Check if password is right encrypted into database', function (done) {
      commonCreateItem.checkItemOkPasswordEncryptedDatabase(done, 'password number 1');
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test password', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test password', function (done) {
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
