const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');
const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('itemProperties | itemlinks type | allowedtypes', function () {
  describe('prepare', function () {
    it('define the type itemlinks', function (done) {
      common.defineValuetype(done, 'itemlinks');
    });

    it('create a new type itemlinks', function (done) {
      common.createType(done, 'itemlinks');
    });

    it('create the property', function (done) {
      common.createProperty(done, null, true, [2, 3]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null, [2, 3]);
    });

    it('Attach a property to the type itemlinks', function (done) {
      common.attachPropertyToType(done);
    });
  });

  describe('update property', function () {
    it('update the property', function (done) {
      common.updateProperty(done, null, 200, null, [2, 4]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null, [2, 4]);
    });
  });

  describe('item, create, allowed: item of user type', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, [2]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [2]);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('item, create, not allowed: item "laptop" id1', function () {
    it('create a new item', function (done) {
      common.createItemWithError(done, [global.itemId1], 'The Value is an id on type not allowed');
    });
  });

  describe('property, update: allow only laptops (id 3)', function () {
    it('update the property', function (done) {
      common.updateProperty(done, null, 200, null, [3]);
    });
    it('Get the property to check allowedtypes is good', function (done) {
      common.checkProperty(done, null, [3]);
    });
  });

  describe('item, create', function () {
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, [global.itemId1, global.itemId3]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId1, global.itemId3]);
    });
  });

  describe('item, update, allowed: item of user type', function () {
    it('update item', function (done) {
      commonCreateItem.updateItem(done, [global.itemId5]);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId5]);
    });
  });

  describe('special item: add only one itemlink', function () {
    it('Update the item with 2 value', function (done) {
      common.addLinkDedicatedEndpoint(done, global.itemId2);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId5, global.itemId2]);
    });
  });

  describe('special item: add only one itemlink but with type not allowed', function () {
    it('add itemlinks with type not allowed', function (done) {
      common.addLinkDedicatedEndpointWithError(done, 2, 'The Value is an id on type not allowed');
    });
  });

  describe('test automatic update of allowedtypes when delete a type', function () {
    it('create a new type', function (done) {
      request
        .post('/v1/config/types')
        .send({ name: 'Firewall' })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.propertyCount(response.body, 1));

          assert(is.integer(response.body.id));
          assert(validator.matches('' + response.body.id, /^\d+$/));
          global.firewallId = response.body.id;
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('create an item', function (done) {
      request
        .post('/v1/items')
        .send({
          name: 'my item',
          type_id: global.firewallId,
        })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.propertyCount(response.body, 2));
          assert(is.integer(response.body.id));
          assert(is.integer(response.body.id_bytype));
          assert(validator.matches('' + response.body.id, /^\d+$/));
          global.itemFirewallId = response.body.id;
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('update the property', function (done) {
      common.updateProperty(done, null, 200, null, [2, global.firewallId, 4]);
    });

    it('Get the property to check value is good', function (done) {
      common.checkProperty(done, null, [2, global.firewallId, 4]);
    });

    it('Update the item with new type item', function (done) {
      common.addLinkDedicatedEndpoint(done, global.itemFirewallId);
    });

    it('Get the item to check value is good', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId5, global.itemId2, global.itemFirewallId]);
    });

    it('soft delete the Firewall type', function (done) {
      request
        .delete('/v1/config/types/' + global.firewallId)
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect('Content-Type', /json/)
        .expect(200)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('permanently delete the Firewall type', function (done) {
      request
        .delete('/v1/config/types/' + global.firewallId)
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect('Content-Type', /json/)
        .expect(200)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('Get the property to check value is good, so without the firewall type, in A REST API', function (done) {
      common.checkProperty(done, null, [2, 4]);
    });

    it('Get the property to check value is good, so without the firewall type, directly in DB', function (done) {
      requestDB
        .get('/allowedtypes/property_id/' + global.propertyvaluesid.toString())
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.equal(2, response.body.count), 'must have only 2 types');
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('Get the item to check value is good (so without the item of the firewalltype)', function (done) {
      commonCreateItem.checkItemOkItemlinks(done, [global.itemId5, global.itemId2]);
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
