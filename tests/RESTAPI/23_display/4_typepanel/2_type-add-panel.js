const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display typepanel | Add typepanel', function () {
  it('create a new panel', function (done) {
    request
      .post('/v1/display/type/panels')
      .send({
        name: 'mynewpanel',
        type_id: global.typeId,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.newPanelId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('Must have the two panels', function (done) {
    request
      .get('/v1/display/type/' + global.typeId + '/panels')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(2, response.body.length), 'must have the two panels');
        const defaultpanel = response.body[0];
        const newpanel = response.body[1];

        assert(is.propertyCount(defaultpanel, 7));
        assert(is.equal(defaultpanel.type_id, global.typeId));
        assert(is.equal(defaultpanel.name, 'Default'));
        assert(is.equal(defaultpanel.displaytype, 'default'));
        assert(is.equal(1, defaultpanel.items.length));

        assert(is.propertyCount(newpanel, 7));
        assert(is.equal(newpanel.type_id, global.typeId));
        assert(is.equal(newpanel.name, 'mynewpanel'));
        assert(is.equal(newpanel.displaytype, 'default'));
        assert(is.equal(0, newpanel.items.length));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
