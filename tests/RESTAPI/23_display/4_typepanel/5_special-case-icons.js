const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display typepanel | special case : icons', function () {
  it('create a new panel with icon name', function (done) {
    request
      .post('/v1/display/type/panels')
      .send({
        name: 'Panel with normal icon',
        type_id: global.typeId,
        icon: 'computer',
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

  it('Must have the last panel with the icon name', function (done) {
    request
      .get('/v1/display/type/' + global.typeId + '/panels')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        const panel = response.body[(response.body.length - 1)];
        assert(is.equal(panel.name, 'Panel with normal icon'));
        assert(is.equal(panel.icon, 'computer'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new panel with empty icon', function (done) {
    request
      .post('/v1/display/type/panels')
      .send({
        name: 'Panel with empty icon',
        type_id: global.typeId,
        icon: '',
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

  it('Must have the last panel with the icon name null', function (done) {
    request
      .get('/v1/display/type/' + global.typeId + '/panels')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        const panel = response.body[(response.body.length - 1)];
        assert(is.equal(panel.name, 'Panel with empty icon'));
        assert(is.null(panel.icon), 'icon must be null');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new panel with icon with "[]"', function (done) {
    request
      .post('/v1/display/type/panels')
      .send({
        name: 'Panel with emptystring array icon',
        type_id: global.typeId,
        icon: '',
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

  it('Must have the last panel with the icon name null instead "[]"', function (done) {
    request
      .get('/v1/display/type/' + global.typeId + '/panels')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        const panel = response.body[(response.body.length - 1)];
        assert(is.equal(panel.name, 'Panel with emptystring array icon'));
        assert(is.null(panel.icon), 'icon must be null');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
