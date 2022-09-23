const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | passwordhash type | working set | default value null', function () {
  describe('prepare', function () {
    it('define the type passwordhash', function (done) {
      common.defineValuetype(done, 'passwordhash');
    });

    it('create a new type passwordhash', function (done) {
      common.createType(done, 'passwordhash');
    });

    it('create the property', function (done) {
      common.createProperty(done, null);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null);
    });

    it('Check if default passwordhash is right null into database', function (done) {
      commonCreateItem.checkDefaultPropertyPasswordHashedDatabase(done, null);
    });

    it('Attach a property to the type passwordhash', function (done) {
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

    it('Check if password is right hashed into database', function (done) {
      commonCreateItem.checkItemOkPasswordHashedDatabase(done, null);
    });
  });

  describe('item, update: passwordhash value', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, 'my new password hash');
    });

    it('Get the item from all items of type to check value is good', function (done) {
      commonCreateItem.checkItemsOkPassword(done, null);
    });

    it('Get the item to check value is null when get only the item', function (done) {
      commonCreateItem.checkItemOkPassword(done, null);
    });

    it('Check if passwordhash is right hashed into database', function (done) {
      commonCreateItem.checkItemOkPasswordHashedDatabase(done, 'my new password hash');
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

    it('Check if password is right hashed into database', function (done) {
      commonCreateItem.checkItemOkPasswordHashedDatabase(done, null);
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test passwordhash', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test passwordhash', function (done) {
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
