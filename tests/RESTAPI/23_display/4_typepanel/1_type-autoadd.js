const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display typepanel | Add type - auto-add typepanel', function () {
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

        assert(is.propertyCount(panel, 7));
        assert(is.number(panel.id));
        assert(is.string(panel.name));
        assert(is.null(panel.icon));
        assert(is.number(panel.position));
        assert(is.string(panel.displaytype));
        assert(is.array(panel.items));

        assert(is.equal(panel.type_id, global.typeId));
        assert(is.equal(panel.name, 'Default'));
        assert(is.equal(panel.displaytype, 'default'));
        assert(is.equal(0, panel.items.length));
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

  it('check if the property has been added in default panel of the type', function (done) {
    request
      .get('/v1/display/type/' + global.typeId + '/panels')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(1, response.body.length));
        const panel = response.body[0];

        assert(is.propertyCount(panel, 7));
        assert(is.number(panel.id));
        assert(is.string(panel.name));
        assert(is.null(panel.icon));
        assert(is.number(panel.position));
        assert(is.string(panel.displaytype));
        assert(is.array(panel.items));
        assert(is.equal(1, panel.items.length), 'must have one property');
        const item = panel.items[0];
        assert(is.equal(0, item.position), 'position must be 0');
        assert(is.equal(10, item.property_id), 'the property id must be: 10');

        assert(is.equal(panel.type_id, global.typeId));
        assert(is.equal(panel.name, 'Default'));
        assert(is.equal(panel.displaytype, 'default'));
      })
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

  it('check if the second property has been added in default panel of the type', function (done) {
    request
      .get('/v1/display/type/' + global.typeId + '/panels')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(1, response.body.length));
        const panel = response.body[0];

        assert(is.propertyCount(panel, 7));
        assert(is.number(panel.id));
        assert(is.string(panel.name));
        assert(is.null(panel.icon));
        assert(is.number(panel.position));
        assert(is.string(panel.displaytype));
        assert(is.array(panel.items));
        assert(is.equal(2, panel.items.length), 'must have two properties');
        const item = panel.items[1];
        assert(is.equal(1, item.position), 'position must be 1');
        assert(is.equal(12, item.property_id), 'the property id must be: 12');

        assert(is.equal(panel.type_id, global.typeId));
        assert(is.equal(panel.name, 'Default'));
        assert(is.equal(panel.displaytype, 'default'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get panelitems of the panel', function (done) {
    request
      .get('/v1/display/type/panels/' + global.defaultpanelId + '/panelitems')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(2, response.body.length));
        let item = response.body[0];
        assert(is.propertyCount(item, 6), 'must have 6 properties');
        assert(is.equal(0, item.position), 'position must be 0');
        assert(is.equal(10, item.property_id), 'the property id must be: 10');
        assert(is.equal(global.defaultpanelId, item.typepanel_id), 'typepanel not right');
        item = response.body[1];
        assert(is.propertyCount(item, 6), 'must have 6 properties');
        assert(is.equal(1, item.position), 'position must be 1');
        assert(is.equal(12, item.property_id), 'the property id must be: 12');
        assert(is.equal(global.defaultpanelId, item.typepanel_id), 'typepanel not right');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('detach the first property to the type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeId + '/property/10')
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

  it('check if the second property is alone in default panel and have now position 0', function (done) {
    request
      .get('/v1/display/type/' + global.typeId + '/panels')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(1, response.body.length));
        const panel = response.body[0];

        assert(is.propertyCount(panel, 7));
        assert(is.number(panel.id));
        assert(is.string(panel.name));
        assert(is.null(panel.icon));
        assert(is.number(panel.position));
        assert(is.string(panel.displaytype));
        assert(is.array(panel.items));
        assert(is.equal(1, panel.items.length), 'must have one property');
        const item = panel.items[0];
        assert(is.equal(0, item.position), 'position must be 0');
        assert(is.equal(12, item.property_id), 'the property id must be: 12');
        global.panelitemprop12Id = item.id;
        assert(is.equal(panel.type_id, global.typeId));
        assert(is.equal(panel.name, 'Default'));
        assert(is.equal(panel.displaytype, 'default'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
