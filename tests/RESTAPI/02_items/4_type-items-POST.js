// import { fakerEN, fakerAR, fakerFR, fakerJA, fakerRU, fakerZH_CN as fakerZH } from '@faker-js/faker';

const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const { fakerEN } = require('@faker-js/faker');
const { fakerAR } = require('@faker-js/faker');
const { fakerFR } = require('@faker-js/faker');
const { fakerJA } = require('@faker-js/faker');
const { fakerRU } = require('@faker-js/faker');
const { fakerZH_CN: fakerZH } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('items | Endpoint /v1/items', function () {
  // Generate random
  for (let i = 1; i <= 60; i++) {
    it('create laptops with random names and serials, also in different langs (' + i + ')', function (done) {
      let faker = fakerEN;
      if (i < 10) {
        faker = fakerEN;
      } else if (i < 20) {
        faker = fakerAR;
      } else if (i < 30) {
        faker = fakerFR;
      } else if (i < 40) {
        faker = fakerJA;
      } else if (i < 50) {
        faker = fakerRU;
      } else if (i < 60) {
        faker = fakerZH;
      }
      const name = faker.word.sample();
      const serial = faker.word.sample() + faker.number.int({ max: 99999 });
      let myId = 0;
      request
        .post('/v1/items')
        .send({ name, type_id: global.mytypeId, properties: [{ property_id: global.propertyid, value: serial }] })
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          assert(is.propertyCount(response.body, 2));
          assert(is.integer(response.body.id));
          assert(is.integer(response.body.id_bytype));
          assert(validator.matches('' + response.body.id, /^\d+$/));
          myId = response.body.id;

          // Test get it
          request
            .get('/v1/items/' + myId)
            .set('Accept', 'application/json')
            .set('Authorization', 'Bearer ' + global.token)
            .expect(200)
            .expect('Content-Type', /json/)
            .expect(function (response) {
              assert(is.not.empty(response.body));
              assert(is.equal(name, response.body.name));
              assert(is.equal(serial, response.body.properties[0].value));
            })
            .end(function (err, response) {
              if (err) {
                return done(err + ' | Response: ' + response.text);
              }
            });
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' ' + name + ' ' + ' | Response: ' + response.text);
          }
          return done();
        });
    });
  }
});
