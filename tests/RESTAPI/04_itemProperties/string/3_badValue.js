const common = require('../common.js');

describe('itemProperties | string type | bad values | create items', function () {
  describe('prepare', function () {
    it('define the type string', function (done) {
      common.defineValuetype(done, 'string');
    });

    it('create a new type string', function (done) {
      common.createType(done, 'string');
    });

    it('create the property', function (done) {
      common.createProperty(done, 'test string default');
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 'test string default');
    });

    it('Attach a property to the type string', function (done) {
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
    it('Soft delete the type: test string', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test string', function (done) {
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
