const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const commonCreateItem = require('../commonCreateItem.js');

const common = require('../common.js');

describe('itemProperties: itemlinks type - bad value type when create an item', function () {
  describe('prepare', function () {
    it('define the type itemlinks', function (done) {
      common.defineValuetype(done, 'itemlinks');
    });

    it('create a new type itemlinks', function (done) {
      common.createType(done, 'itemlinks');
    });

    it('create the property', function (done) {
      common.createProperty(done, [global.itemId1]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, [global.itemId1]);
    });

    it('Attach a property to the type itemlinks', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('item, create: wrong values => error', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessageDefault, errorMessage }) => {
      errorMessage = errorMessage.replace('{{id}}', global.propertyvaluesid);
      it('create an item with bad value ' + description + ' => error', function (done) {
        request
          .post('/v1/items')
          .send({
            name: 'test itemlinks',
            type_id: global.propertytypesid,
            properties: [
              {
                property_id: global.propertyvaluesid,
                value,
              },
            ],
          })
          .set('Accept', 'application/json')
          .set('Authorization', 'Bearer ' + global.token)
          .expect(400)
          .expect('Content-Type', /json/)
          .expect(function (response) {
            assert(is.propertyCount(response.body, 2));
            assert(validator.equals(response.body.status, 'error'));
            assert(is.startWith(response.body.message, errorMessage));
          })
          .end(function (err, response) {
            if (err) {
              return done(err + ' | Response: ' + response.text);
            }
            return done();
          });
      });
    });
  });

  describe('item, update: wrong values => error', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, [global.itemId1, global.itemId2]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId1, global.itemId2]);
    });

    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessageDefault, errorMessage }) => {
      it('update the item property ' + description + ' => error', function (done) {
        const errorValues = errorMessage.split(' (');
        common.updateItemWithError(done, value, errorValues[0]);
      });
    });
  });

  describe('property item, add special endpoint: wrong values => error', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProviderItemlink.forEach(({ description, value, errorMessage }) => {
      it('add item the item property ' + description + ' => error', function (done) {
        common.addLinkDedicatedEndpointWithError(done, value, errorMessage);
      });
    });
  });

  describe('clean', function () {
    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Soft delete the type: test itemlinks', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test itemlinks', function (done) {
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
