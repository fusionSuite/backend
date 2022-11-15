const common = require('../common.js');

describe('itemProperties | boolean type | bad values | create items', function () {
  describe('prepare', function () {
    it('define the type boolean', function (done) {
      common.defineValuetype(done, 'boolean');
    });

    it('create a new type', function (done) {
      common.createType(done, 'boolean');
    });

    it('create the property ', function (done) {
      common.createProperty(done, true);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, true);
    });

    it('Attach a property to the type', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: multiple bad values', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(function ({ description, value, errorMessage }) {
      errorMessage = errorMessage.replace(' Default ', ' Value ');
      it('try create a new item but return error (' + description + ') => error', function (done) {
        common.createItemWithError(done, value, errorMessage);
      });
    });
  });

  describe('clean', function () {
    it('Soft delete the type: test boolean', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test boolean', function (done) {
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
