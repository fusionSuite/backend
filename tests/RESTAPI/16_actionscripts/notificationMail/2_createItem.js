const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestMb = supertest('http://127.0.0.1:2525');

describe('Endpoint /v1/items - test notifications SMTP', function () {
  it('delete imposters of mountebank', function (done) {
    requestMb
      .delete('/imposters/10025/savedRequests')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body.requests, 0));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new item', function (done) {
    request
      .post('/v1/items')
      .send({ name: 'Laptop 0021', type_id: 3, properties: [{ property_id: 10, value: 'serialxxxxxx' }] })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(is.integer(response.body.id_bytype));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('verify into SMTP mountbank requet done', function (done) {
    requestMb
      .get('/imposters/10025')
      .set('Accept', 'application/json')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.string(response.body.protocol));
        assert(validator.equals(response.body.protocol, 'smtp'));

        assert(is.integer(response.body.numberOfRequests));
        assert(response.body.numberOfRequests === 1);

        const req = response.body.requests[0];
        assert(is.string(req.from.address));
        assert(validator.equals(req.from.address, 'john@rambo.com'));
        assert(is.string(req.from.name));
        assert(validator.equals(req.from.name, 'John Rambo'));

        // TODO manage the to (to.address and to.name)

        assert(is.string(req.subject));
        assert(validator.equals(req.subject, 'New Laptop added'));

        assert(is.string(req.html));
        assert(validator.matches(req.html, /(A new laptop has been added into FusionSuite.)/));
        assert(validator.matches(req.html, /(Laptop 0021)/));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
