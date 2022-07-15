const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('Endpoint /v1/items', function() {

  it('create laptops with random names and serials, also in different langs', function(done) {
    // Generate random
    for (var i=1;i<=60; i++) {
      if (i < 10) {
        faker.setLocale('en');
      } else if (i < 20) {
        faker.setLocale('ar');
      } else if (i < 30) {
        faker.setLocale('fr');
      } else if (i < 40) {
        faker.setLocale('ja');
      } else if (i < 50) {
        faker.setLocale('ru');
      } else if (i < 60) {
        faker.setLocale('zh_CN');
      }
      let name = faker.random.word();
      let serial = faker.random.word() + faker.datatype.number();
      let myId = 0;
      request
      .post('/v1/items')
      .send({name: name,type_id: 2,properties:[{property_id:global.propertyid,value:serial}]})
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(is.integer(response.body.id_bytype));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        myId = response.body.id;

        // Test get it
        request
        .get('/v1/items/'+myId)
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function(response) {
          assert(is.not.empty(response.body));
          assert(is.equal(name, response.body.name));
          assert(is.equal(serial, response.body.properties[0].value));
        })
        .end(function(err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
        });
      })
      .end(function(err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
      });
    }
    return done();
  });
});
