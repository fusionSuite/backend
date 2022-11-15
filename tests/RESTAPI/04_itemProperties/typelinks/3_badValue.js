const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | typelinks type | bad values | create items', function () {
  describe('prepare', function () {
    it('define the type typelinks', function (done) {
      common.defineValuetype(done, 'typelinks');
    });

    it('create a new type typelinks', function (done) {
      common.createType(done, 'typelinks');
    });

    it('create the property', function (done) {
      common.createProperty(done, [1, 2]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, [1, 2]);
    });

    it('Attach a property to the type typelinks', function (done) {
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

  describe('item, create: types id', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, [1, 2]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkTypelinks(done, [1, 2]);
    });
  });

  describe('try add typelink but have error', function () {
    it('try add a new typelink but return error (null value)', function (done) {
      common.addLinkDedicatedEndpointWithError(done, null, 'The Value is required');
    });

    it('try add a new typelink but return error (empty string value)', function (done) {
      common.addLinkDedicatedEndpointWithError(done, '', 'The Value is required');
    });

    it('try add a new typelink but return error (if not exists)', function (done) {
      common.addLinkDedicatedEndpointWithError(done, 54964, 'The Value is an id than does not exist');
    });

    it('try add a new typelink but return error (0 value)', function (done) {
      common.addLinkDedicatedEndpointWithError(done, 0, 'The Value is an id than does not exist');
    });

    it('try add a new typelink but return error (id has string)', function (done) {
      common.addLinkDedicatedEndpointWithError(done, '5', 'The Value is not valid type');
    });

    it('try add a new typelink but return error (negative id)', function (done) {
      common.addLinkDedicatedEndpointWithError(done, -3, 'The Value is not valid format');
    });
  });

  describe('try delete typelink but have error', function () {
    it('try delete a typelink but return error (null value)', function (done) {
      common.deleteLinkDedicatedEndpointWithError(done, null, 405, 'Method not allowed. Must be one of: OPTIONS');
    });

    it('try delete a typelink but return error (0 value)', function (done) {
      common.deleteLinkDedicatedEndpointWithError(done, 0, 400, 'The typelink is an id than does not exist');
    });

    it('try delete a typelink but return error (negative id)', function (done) {
      common.deleteLinkDedicatedEndpointWithError(done, -4, 405, 'Method not allowed. Must be one of: OPTIONS');
    });

    it('try delete a typelink but return error (not exist id)', function (done) {
      common.deleteLinkDedicatedEndpointWithError(done, 598659, 400, 'The typelink is an id than does not exist');
    });
  });

  describe('clean', function () {
    it('Soft delete the type: test typelinks', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test typelinks', function (done) {
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
