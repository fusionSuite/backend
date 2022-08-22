const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('Endpoint /v1/userparams', function() {
  it('respond with json containing the userparams', function(done) {
    request
    .get('/v1/userparams')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      const resp = response.body;
      assert(is.propertyCount(resp, 2));
      assert(is.propertyDefined(resp, 'itemlist'));
      assert(is.propertyCount(resp.itemlist, 2));
      assert(is.number(resp.itemlist.id));
      assert(is.above(resp.itemlist.id, 1));
      assert(is.propertyDefined(resp.itemlist, 'properties'));
      assert(is.propertyCount(resp.itemlist.properties, 4));
      assert(is.number(resp.itemlist.properties.typeId));
      assert(is.above(resp.itemlist.properties.typeId, 1));
      assert(is.number(resp.itemlist.properties.elementsPerPage));
      assert(is.above(resp.itemlist.properties.elementsPerPage, 1));
      assert(is.number(resp.itemlist.properties.propertiesOrder));
      assert(is.above(resp.itemlist.properties.propertiesOrder, 1));
      assert(is.number(resp.itemlist.properties.propertiesHidden));
      assert(is.above(resp.itemlist.properties.propertiesHidden, 1));

      assert(is.propertyDefined(resp, 'csvimport'));
      assert(is.propertyCount(resp.csvimport, 2));
      assert(is.number(resp.csvimport.id));
      assert(is.above(resp.csvimport.id, 1));
      assert(is.propertyDefined(resp.csvimport, 'properties'));
      assert(is.propertyCount(resp.csvimport.properties, 3));
      assert(is.number(resp.csvimport.properties.typeId));
      assert(is.above(resp.csvimport.properties.typeId, 1));
      assert(is.number(resp.csvimport.properties.mappingCols));
      assert(is.above(resp.csvimport.properties.mappingCols, 1));
      assert(is.number(resp.csvimport.properties.joiningFields));
      assert(is.above(resp.csvimport.properties.joiningFields, 1));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
});

