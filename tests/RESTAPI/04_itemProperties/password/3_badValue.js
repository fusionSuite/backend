const common = require('../common.js');

/**
* /v1/types endpoint
*/
describe('itemProperties | password type | bad values | create items', function () {
  describe('prepare', function () {
    it('define the type password', function (done) {
      common.defineValuetype(done, 'password');
    });

    it('create a new type password', function (done) {
      common.createType(done, 'password');
    });

    it('create the property', function (done) {
      common.createProperty(done, 'test password');
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 'test password');
    });

    it('Attach a property to the type password', function (done) {
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
