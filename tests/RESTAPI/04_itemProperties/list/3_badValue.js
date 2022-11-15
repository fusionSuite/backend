const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const common = require('../common.js');

describe('Test itemProperties | list type | bad values | create items', function () {
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
            listvalues: ['list1', 'list2'],
            unit: '',
            default: 'list2',
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

  describe('item, create: wrong values => error', function () {
    // eslint-disable-next-line mocha/no-setup-in-describe
    global.dataProvider.forEach(({ description, value, errorMessageDefault, errorMessage }) => {
      const errorValues = errorMessage.split(' (');
      it('try create a new item but return error (' + description + ')', function (done) {
        common.createItemWithError(done, value, errorValues[0]);
      });
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
