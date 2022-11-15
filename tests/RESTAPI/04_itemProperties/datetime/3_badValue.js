const common = require('../common.js');

describe('itemProperties | datetime type | bad values | create items', function () {
  describe('prepare', function () {
    it('define the type datetime', function (done) {
      common.defineValuetype(done, 'datetime');
    });

    it('create a new type datetime', function (done) {
      common.createType(done, 'datetime');
    });

    it('create the property', function (done) {
      common.createProperty(done, '2022-04-25 21:02:12');
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, '2022-04-25 21:02:12');
    });

    it('Attach a property to the type datetime', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: multiple bad values', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessage }) => {
      errorMessage = errorMessage.replace(' Default ', ' Value ');
      it('try create a new item but return error (' + description + ')', function (done) {
        common.createItemWithError(done, value, errorMessage);
      });
    });
  });

  describe('clean', function () {
    it('Soft delete the type: test datetime', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test datetime', function (done) {
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
