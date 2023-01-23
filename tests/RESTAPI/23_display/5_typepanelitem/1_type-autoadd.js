const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display typepanelitem | Add type - auto-add typepanel', function () {
  it('create a new type', function (done) {
    request
      .post('/v1/config/types')
      .send({ name: 'Spaceship' })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.typeId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('Must have typepanel created, named "Default"', function (done) {
    request
      .get('/v1/display/type/' + global.typeId + '/panels')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(1, response.body.length));
        const panel = response.body[0];
        assert(is.number(panel.id));
        global.defaultpanelId = panel.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('attach a property to the type', function (done) {
    request
      .post('/v1/config/types/' + global.typeId + '/property/10')
      .send({ name: 'Spaceship' })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('attach a second property to the type', function (done) {
    request
      .post('/v1/config/types/' + global.typeId + '/property/12')
      .send({ name: 'Spaceship' })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('attach a third property to the type', function (done) {
    request
      .post('/v1/config/types/' + global.typeId + '/property/13')
      .send({ name: 'Spaceship' })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get the ids of the panelitems', function (done) {
    request
      .get('/v1/display/type/' + global.typeId + '/panels')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(1, response.body.length));
        const panel = response.body[0];
        assert(is.array(panel.items));
        let panelitem = panel.items[0];
        global.panelitem1id = panelitem.id;

        panelitem = panel.items[1];
        global.panelitem2id = panelitem.id;

        panelitem = panel.items[2];
        global.panelitem3id = panelitem.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
