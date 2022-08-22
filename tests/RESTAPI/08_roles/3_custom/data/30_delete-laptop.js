const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/items endpoint
*/

describe('roles | custom > data | delete laptops', function() {
  it('soft delete the laptop', function(done) {
    request
    .delete('/v1/items/' + global.item1Id)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect('Content-Type', /json/)
    // disable expect, because can be deleted or not, so need to be sure it's deleted
    // .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('permanently delete the laptop', function(done) {
    request
    .delete('/v1/items/' + global.item1Id)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect('Content-Type', /json/)
    // disable expect, because can be deleted or not, so need to be sure it's deleted
    // .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});
