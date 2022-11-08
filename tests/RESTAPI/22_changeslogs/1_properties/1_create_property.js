const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('changes | properties | create a property', function () {
  it('initial number of changes rows in database table', function (done) {
    requestDB
      .get('/count/changes')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt = response.body.count;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('create a new property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'Test for string',
        internalname: 'testforstring',
        valuetype: 'string',
        regexformat: '',
        listvalues: [],
        unit: '',
        default: 'default value',
        description: 'Test of the type string',
        canbenull: false,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get the property and check if changes empty', function (done) {
    request
      .get('/v1/config/properties/' + global.propertyId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('Test for string', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table - must not have more changes', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(global.changesCnt, response.body.count));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });
});
