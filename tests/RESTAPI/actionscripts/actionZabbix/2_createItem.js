const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestMb = supertest('http://127.0.0.1:2525');

/**
* /v1/types endpoint
*/
describe('actionscripts/actionZabbix - Test the rule', function() {

  it('delete imposters of mountebank', function(done) {
    requestMb
    .delete('/imposters/10800/savedRequests')
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body.requests, 0));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new item', function(done) {
    request
    .post('/v1/items')
    .send({name: 'Laptop 0025',type_id: 2,properties:[{property_id:5,value:"serialxxxxxx"}]})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));

      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.id = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('verify into Zabbix/HTTP mountbank requet done', function(done) {
    requestMb
    .get('/imposters/10800')
    .set('Accept', 'application/json')
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.string(response.body.protocol));
      assert(validator.equals(response.body.protocol, 'http'));

      assert(is.integer(response.body.numberOfRequests));
      assert(response.body.numberOfRequests === 3);

      let req = response.body.requests[2];

      assert(is.string(req.method));
      assert(validator.equals(req.method, 'POST'));
      assert(validator.matches(req.body, /Laptop 0025/));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

});

