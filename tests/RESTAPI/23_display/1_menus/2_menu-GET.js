const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display menu | Get menu', function () {
  it('respond with json containing the list of menus', function (done) {
    request
      .get('/v1/display/menu')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        const menu = response.body[0]; // assets menu
        assert(is.propertyCount(menu, 7));
        assert(is.number(menu.id));
        assert(is.string(menu.name));
        assert(is.null(menu.icon));
        assert(is.number(menu.position));
        assert(validator.isISO8601(menu.created_at));
        assert(validator.isISO8601(menu.updated_at));
        assert(is.array(menu.items));

        assert(is.equal(menu.id, global.id));
        assert(validator.equals(menu.name, 'Assets'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
