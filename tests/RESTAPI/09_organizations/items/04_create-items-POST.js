const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('organizations | items | create items', function() {

  it ('create an item in top level of oganization', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'myitem1',
      type_id: global.typeId,
      organization_id: 1
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(is.integer(response.body.id));
      assert(is.integer(response.body.id_bytype));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.myitem1 = response.body.id;

      // Test get it
      request
      .get('/v1/items/'+global.myitem1)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.not.empty(response.body));
        assert(is.equal(1, response.body.organization.id));
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
      return done();
    });
  });

  it ('create an item in top level of oganization with sub to true', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'myitemSub1',
      type_id: global.typeId,
      organization_id: 1,
      sub_organization: true
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(is.integer(response.body.id));
      assert(is.integer(response.body.id_bytype));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.myitemSub1 = response.body.id;

      // Test get it
      request
      .get('/v1/items/'+global.myitemSub1)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.not.empty(response.body));
        assert(is.equal(1, response.body.organization.id));
        assert(is.boolean(response.body.sub_organization));
        assert(is.equal(true, response.body.sub_organization));
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
      return done();
    });
  });


  it ('create a myitem in second level of oganization', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'myitem2',
      type_id: global.typeId,
      organization_id: global.subOrg1
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(is.integer(response.body.id));
      assert(is.integer(response.body.id_bytype));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.myitem2 = response.body.id;

      // Test get it
      request
      .get('/v1/items/'+global.myitem2)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.not.empty(response.body));
        assert(is.equal(global.subOrg1, response.body.organization.id));
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
      return done();
    });
  });

  it ('create a myitem in third level of oganization', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'myitem3',
      type_id: global.typeId,
      organization_id: global.subOrg2
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(is.integer(response.body.id));
      assert(is.integer(response.body.id_bytype));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.myitem3 = response.body.id;

      // Test get it
      request
      .get('/v1/items/'+global.myitem3)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.not.empty(response.body));
        assert(is.equal(global.subOrg2, response.body.organization.id));
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
      return done();
    });
  });

});
