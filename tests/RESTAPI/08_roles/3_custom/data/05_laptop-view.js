const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('roles | custom > data | check view permission on laptop', function() {

  it('set permission to laptop to view only', function(done) {
    request
    .patch('/v1/config/roles/'+global.roleId+'/permissiondata/'+global.permissiondataId)
    .send({
      permission: 'none',
      view: true
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('create a laptop => permission error', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'myitem2',
      type_id: 3,
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(401)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'No permission on this item'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('update a laptop => permission error', function(done) {
    request
    .patch('/v1/items/'+global.item1Id)
    .send({
      name: 'myitem1ter'
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(401)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'No permission on this item'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('get laptops', function(done) {
    request
    .get('/v1/items/type/3')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(200)
    .expect('Content-Type', /json/)
    // .expect(function(response) {
    //   assert(is.propertyCount(response.body, 2));
    //   assert(validator.equals(response.body.status, 'error'));
    //   assert(validator.equals(response.body.message, 'No permission on this item'));
    // })
  .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('get the specific laptop', function(done) {
    request
    .get('/v1/items/'+global.item1Id)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.tokenUser1)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.equal('myitem1bis', response.body.name));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

});
