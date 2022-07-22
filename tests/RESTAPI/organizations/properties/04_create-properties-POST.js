const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('organizations | properties | create properties', function() {

  it ('create a property in top level of oganization', function(done) {
    request
    .post('/v1/config/properties')
    .send({
      name: 'myprop1',
      internalname: 'testformypropone',
      organization_id: 1,
      valuetype: 'string',
      listvalues: [],
      default: ''
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.myprop1 = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('check if the property in top level of oganization is created', function(done) {
    request
    .get('/v1/config/properties/'+global.myprop1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.equal(1, response.body.organization.id));
      assert(!response.body.sub_organization);
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('create a property in top level of oganization with sub to true', function(done) {
    request
    .post('/v1/config/properties')
    .send({
      name: 'mypropSub1',
      internalname: 'testformypropsubone',
      organization_id: 1,
      sub_organization: true,
      valuetype: 'string',
      listvalues: [],
      default: ''
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.mypropSub1 = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it ('check if the property in top level of oganization with sub to true is created', function(done) {
    request
    .get('/v1/config/properties/'+global.mypropSub1)
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
      return done();
    });
  });

  it ('create a property in second level of oganization', function(done) {
    request
    .post('/v1/config/properties')
    .send({
      name: 'myprop2',
      internalname: 'testformyproptwo',
      organization_id: global.subOrg1,
      valuetype: 'string',
      listvalues: [],
      default: ''
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.myprop2 = response.body.id;

      // Test get it
      request
      .get('/v1/config/properties/'+global.myprop2)
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

});
