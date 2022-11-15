const common = require('../common.js');

describe('itemProperties | text type | bad value | create items', function () {
  describe('prepare', function () {
    it('define the type text', function (done) {
      common.defineValuetype(done, 'text');
    });

    it('create a new type text', function (done) {
      common.createType(done, 'text');
    });

    it('create the property', function (done) {
      common.createProperty(done, 'test texte default\nmultiple lines..');
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, 'test texte default\nmultiple lines..');
    });

    it('Attach a property to the type text', function (done) {
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
    it('Soft delete the type: test text', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test text', function (done) {
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
