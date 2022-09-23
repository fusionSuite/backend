const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | passwordhash type | bad values | create items', function () {
  describe('prepare', function () {
    it('define the type passwordhash', function (done) {
      common.defineValuetype(done, 'passwordhash');
    });

    it('create a new type passwordhash', function (done) {
      common.createType(done, 'passwordhash');
    });

    it('create the property', function (done) {
      common.createProperty(done, 'test passwordhash');
    });

    it('Get the property to check value is null', function (done) {
      common.checkProperty(done, null);
    });

    it('Check if default passwordhash is right defined into database', function (done) {
      commonCreateItem.checkDefaultPropertyPasswordHashedDatabase(done, 'test passwordhash');
    });

    it('Attach a property to the type passwordhash', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: wrong values => error', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessage }) => {
      errorMessage = errorMessage.replace(' Default ', ' Value ');
      it('try create a new item but return error (' + description + ')', function (done) {
        common.createItemWithError(done, value, errorMessage);
      });
    });
  });

  describe('clean', function () {
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
