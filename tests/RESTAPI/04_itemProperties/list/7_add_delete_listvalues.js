const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');
const commonCreateItem = require('../commonCreateItem.js');

describe('itemProperties | list type | add and remove listvalues', function () {
  describe('prepare', function () {
    it('define the type list', function (done) {
      common.defineValuetype(done, 'list');
    });

    it('create a new type list', function (done) {
      common.createType(done, 'list');
    });

    it('create a new property - type list', function (done) {
      request
        .post('/v1/config/properties')
        .send(
          {
            name: 'Test for list',
            internalname: 'testforlist',
            valuetype: 'list',
            regexformat: '',
            listvalues: ['list1', 'list2', 'list3', 'list4'],
            unit: '',
            default: null,
            description: 'Test of the type list',
          })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.propertyCount(response.body, 1));
          assert(is.integer(response.body.id));
          assert(validator.matches('' + response.body.id, /^\d+$/));
          global.propertyvaluesid = response.body.id;
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('Attach a property to the type list', function (done) {
      common.attachPropertyToType(done);
    });

    it('Get the list of ids', function (done) {
      common.getListIds(done);
    });
  });

  describe('add and remove a listvalue to the property', function () {
    it('add a new listvalue', function (done) {
      request
        .post('/v1/config/properties/' + global.propertyvaluesid.toString() + '/listvalues')
        .send(
          {
            value: 'my very new value',
          })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('check valuelists updated when get the property', function (done) {
      request
        .get('/v1/config/properties/' + global.propertyvaluesid.toString())
        .send()
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.not.empty(response.body), 'The body must contain something');
          assert(is.equal(5, response.body.listvalues.length), 'must have 5 values');
          assert(is.equal('list1', response.body.listvalues[0].value));
          assert(is.equal('list2', response.body.listvalues[1].value));
          assert(is.equal('list3', response.body.listvalues[2].value));
          assert(is.equal('list4', response.body.listvalues[3].value));
          assert(is.equal('my very new value', response.body.listvalues[4].value));

          global.list1Id = response.body.listvalues[0].id;
          global.list2Id = response.body.listvalues[1].id;
          global.list3Id = response.body.listvalues[2].id;
          global.list4Id = response.body.listvalues[3].id;
          global.listverynewvalueId = response.body.listvalues[4].id;
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('delete the listvalue list3', function (done) {
      request
        .delete('/v1/config/properties/' + global.propertyvaluesid.toString() + '/listvalues/' + global.list3Id.toString())
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('check valuelists without list3 (deleted) when get the property', function (done) {
      request
        .get('/v1/config/properties/' + global.propertyvaluesid.toString())
        .send()
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.not.empty(response.body), 'The body must contain something');
          assert(is.equal(4, response.body.listvalues.length), 'must have 4 values');
          assert(is.equal('list1', response.body.listvalues[0].value));
          assert(is.equal('list2', response.body.listvalues[1].value));
          assert(is.equal('list4', response.body.listvalues[2].value));
          assert(is.equal('my very new value', response.body.listvalues[3].value));
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });
  });

  describe('create item and delete listvalue - property can be null - item property: list1 - property default value: null', function () {
    // create item
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 'list1');
    });

    it('Get the item to check value is list1', function (done) {
      commonCreateItem.checkItemOkList(done, global.list1Id);
    });

    it('update property to have default value: null', function (done) {
      common.updateProperty(done, null, 200);
    });

    it('check property to have default value to: null', function (done) {
      common.checkProperty(done, null);
    });

    it('delete the listvalue list1', function (done) {
      request
        .delete('/v1/config/properties/' + global.propertyvaluesid.toString() + '/listvalues/' + global.list1Id.toString())
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('Get the item to check value is null', function (done) {
      commonCreateItem.checkItemOkList(done, null);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('create item and delete listvalue - property can be null - item property: list2 - property default value: my very new value', function () {
    // create item
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 'list2');
    });

    it('Get the item to check value is list2', function (done) {
      commonCreateItem.checkItemOkList(done, global.list2Id);
    });

    it('update property to have default value to: my very new value', function (done) {
      common.updateProperty(done, 'my very new value', 200);
    });

    it('check property to have default value to: my very new value', function (done) {
      common.checkProperty(done, global.listverynewvalueId);
    });

    it('delete the listvalue list2', function (done) {
      request
        .delete('/v1/config/properties/' + global.propertyvaluesid.toString() + '/listvalues/' + global.list2Id.toString())
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('Get the item to check value is: my very new value (default value)', function (done) {
      commonCreateItem.checkItemOkList(done, global.listverynewvalueId);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('create item and delete listvalue - property can\'t be null - item property: list4 - property default value: list4 (must be error)', function () {
    // create item
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 'list4');
    });

    it('Get the item to check value is list4', function (done) {
      commonCreateItem.checkItemOkList(done, global.list4Id);
    });

    it('update property to have can\'t be null and default value to: list4', function (done) {
      request
        .patch('/v1/config/properties/' + global.propertyvaluesid.toString())
        .send({ default: 'list4', canbenull: false })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('check property to have default value to: my very new value', function (done) {
      common.checkProperty(done, global.list4Id);
    });

    it('delete the listvalue list4 (and the default) => must be error because can\'t be null', function (done) {
      request
        .delete('/v1/config/properties/' + global.propertyvaluesid.toString() + '/listvalues/' + global.list4Id.toString())
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(401)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.propertyCount(response.body, 2));
          assert(validator.equals(response.body.status, 'error'));
          assert(validator.equals(response.body.message, 'The property can\'t be null and the default value is this listvalue'));
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('create item and delete listvalue - property can\'t be null - item property: list4 - property default value: my very new value', function () {
    // create item
    it('create a new item', function (done) {
      commonCreateItem.createItem(done, true, 'list4');
    });

    it('Get the item to check value is list4', function (done) {
      commonCreateItem.checkItemOkList(done, global.list4Id);
    });

    it('update property to have can\'t be null and default value to: my very new value', function (done) {
      request
        .patch('/v1/config/properties/' + global.propertyvaluesid.toString())
        .send({ default: 'my very new value', canbenull: false })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('check property to have default value to: my very new value', function (done) {
      common.checkProperty(done, global.listverynewvalueId);
    });

    it('delete the listvalue list4', function (done) {
      request
        .delete('/v1/config/properties/' + global.propertyvaluesid.toString() + '/listvalues/' + global.list4Id.toString())
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('Get the item to check value is: my very new value', function (done) {
      commonCreateItem.checkItemOkList(done, global.listverynewvalueId);
    });

    it('Soft delete the item', function (done) {
      common.deleteItem(done);
    });

    it('Hard delete the item', function (done) {
      common.deleteItem(done);
    });
  });

  describe('clean', function () {
    it('Soft delete the type: test list', function (done) {
      common.deleteType(done);
    });

    it('Hard delete the type: test list', function (done) {
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
