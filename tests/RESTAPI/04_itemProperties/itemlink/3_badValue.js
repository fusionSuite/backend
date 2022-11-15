const common = require('../common.js');

describe('itemProperties | itemlink type | bad value | create items', function () {
  describe('prepare', function () {
    it('define the type itemlink', function (done) {
      common.defineValuetype(done, 'itemlink');
    });

    it('create a new type itemlink', function (done) {
      common.createType(done, 'itemlink');
    });

    it('create a property', function (done) {
      common.createProperty(done, global.itemId1);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, global.itemId1);
    });

    it('Attach a property to the type itemlink', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: multiple bad values', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessage }) => {
      errorMessage = errorMessage.replace(' Default ', ' Value ');
      it('create item ' + description + ' => error', function (done) {
        common.createItemWithError(done, value, errorMessage);
      });
    });
  });

  describe('clean', function () {
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
