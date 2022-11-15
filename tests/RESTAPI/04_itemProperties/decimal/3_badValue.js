const common = require('../common.js');

describe('itemProperties | decimal type | bad values | create items', function () {
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

  describe('item, create: multiple bad values', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessage }) => {
      it('try create a new item but return error (' + description + ')', function (done) {
        common.createItemWithError(done, value, errorMessage);
      });
    });
  });

  describe('clean', function () {
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
