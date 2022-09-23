const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | password type | working set | default value null', function () {
  describe('prepare', function () {
    it('define the type password', function (done) {
      common.defineValuetype(done, 'password');
    });

    it('create a new type password', function (done) {
      common.createType(done, 'password');
    });

    it('create the property', function (done) {
      common.createProperty(done, null);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null);
    });

    it('Check if default password is right null into database', function (done) {
      commonCreateItem.checkDefaultPropertyPasswordEncryptedDatabase(done, null);
    });

    it('Attach a property to the type password', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: null value', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, null);
    });

    it('Get the item from all items of type to check value is good', function (done) {
      commonCreateItem.checkItemsOkPassword(done, null);
    });

    it('Get the item to check value is null when get only the item', function (done) {
      commonCreateItem.checkItemOkPassword(done, null);
    });

    it('Check if password is right encrypted into database', function (done) {
      commonCreateItem.checkItemOkPasswordEncryptedDatabase(done, null);
    });
  });

  describe('item, update: password value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, 'my new password');
    });

    it('Get the item from all items of type to check value is good', function (done) {
      commonCreateItem.checkItemsOkPassword(done, null);
    });

    it('Get the item to check value is `my new password` when get only the item', function (done) {
      commonCreateItem.checkItemOkPassword(done, 'my new password');
    });

    it('Check if password is right encrypted into database', function (done) {
      commonCreateItem.checkItemOkPasswordEncryptedDatabase(done, 'my new password');
    });
  });

  describe('item, update: return to default value', function () {
    it('Update the item to default value', function (done) {
      commonCreateItem.updateItemToDefault(done);
    });

    it('Get the item from all items of type to check value is good', function (done) {
      commonCreateItem.checkItemsOkPassword(done, null);
    });

    it('Get the item to check value is null when get only the item', function (done) {
      commonCreateItem.checkItemOkPassword(done, null);
    });

    it('Check if password is right encrypted into database', function (done) {
      commonCreateItem.checkItemOkPasswordEncryptedDatabase(done, null);
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
