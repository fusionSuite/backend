const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display menuitem | Delete a type used in menuitem', function () {
  it('soft delete the Firewall type', function (done) {
    request
      .delete('/v1/config/types/' + global.firewallId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect('Content-Type', /json/)
      .expect(200)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('permanently delete the Firewall type', function (done) {
    request
      .delete('/v1/config/types/' + global.firewallId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect('Content-Type', /json/)
      .expect(200)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('respond with json containing only 1 item', function (done) {
    request
      .get('/v1/display/menu/item')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(1, response.body.length), 'must have 1 item in this menu');
        const menu = response.body[0];
        assert(is.propertyCount(menu, 8));
        assert(is.number(menu.id));
        assert(is.string(menu.name));
        assert(is.null(menu.icon));
        assert(is.object(menu.type));
        assert(is.number(menu.type.id));
        assert(is.number(menu.position));
        assert(is.number(menu.menu_id));
        assert(validator.isISO8601(menu.created_at));
        assert(validator.isISO8601(menu.updated_at));

        assert(is.equal(menu.id, global.id));
        assert(validator.equals(menu.name, 'Laptop'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
