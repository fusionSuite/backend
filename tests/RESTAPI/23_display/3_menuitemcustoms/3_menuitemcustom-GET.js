const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display menuitemcustom | Get menuitemcustom', function () {
  it('respond with json containing the list of item custom', function (done) {
    request
      .get('/v1/display/menu/custom')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(2, response.body.length));
        const menucustom = response.body[0];
        assert(is.propertyDefined(menucustom, 'id'));
        assert(is.propertyDefined(menucustom, 'position'));
        assert(is.propertyDefined(menucustom, 'menuitem'));
        assert(is.propertyCount(menucustom, 5));

        assert(is.equal(menucustom.menuitem.id, global.id));
        assert(is.equal(menucustom.menuitem.name, 'Laptop'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('respond with json containing the list of item custom of user1', function (done) {
    request
      .get('/v1/display/menu/custom')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.tokenUser1)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(1, response.body.length));
        const menucustom = response.body[0];
        assert(is.propertyDefined(menucustom, 'id'));
        assert(is.propertyDefined(menucustom, 'position'));
        assert(is.propertyDefined(menucustom, 'menuitem'));
        assert(is.propertyCount(menucustom, 5));

        assert(is.equal(menucustom.menuitem.id, global.idBis));
        assert(is.equal(menucustom.menuitem.name, 'Laptop bis'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
