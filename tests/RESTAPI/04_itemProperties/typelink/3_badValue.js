const common = require('../common.js');

describe('itemProperties | typelink type | bad values | create items', function () {
  describe('prepare', function () {
    it('define the type typelink', function (done) {
      common.defineValuetype(done, 'typelink');
    });

    it('create a new type typelink', function (done) {
      common.createType(done, 'typelink');
    });

    it('create the property', function (done) {
      common.createProperty(done, 1); // with type with id=1 (organization)
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 1);
    });

    it('Attach a property to the type typelink', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('Multiple test to create items', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessage }) => {
      errorMessage = errorMessage.replace(' Default ', ' Value ');
      it('try create a new item but return error (' + description + ')', function (done) {
        common.createItemWithError(done, value, errorMessage);
      });
    });
  });

  describe('clean', function () {
    it('Soft delete the type: test typelink', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test typelink', function (done) {
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
