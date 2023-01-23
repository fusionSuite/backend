const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display typepanel | Transfert properties to panels', function () {
  it('attach a second property to the type', function (done) {
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

  it('put the first property into the new panel', function (done) {
    request
      .patch('/v1/display/type/panelitems/' + global.panelitemprop12Id)
      .send({
        typepanel_id: global.newPanelId,
      })
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

  it('check if the first property has been moved in the second panel', function (done) {
    request
      .get('/v1/display/type/' + global.typeId + '/panels')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(2, response.body.length));
        const panel = response.body[0];
        assert(is.propertyCount(panel, 7));
        assert(is.number(panel.id));
        assert(is.string(panel.name));
        assert(is.null(panel.icon));
        assert(is.number(panel.position));
        assert(is.string(panel.displaytype));
        assert(is.equal(panel.type_id, global.typeId));
        assert(is.equal(panel.name, 'Default'));
        assert(is.equal(panel.displaytype, 'default'));
        assert(is.array(panel.items));
        assert(is.equal(1, panel.items.length), 'must have one property');
        let item = panel.items[0];
        assert(is.equal(0, item.position), 'position must be 0');
        assert(is.equal(10, item.property_id), 'the property id must be: 10');

        const newpanel = response.body[1];
        assert(is.propertyCount(newpanel, 7));
        assert(is.number(newpanel.id));
        assert(is.string(newpanel.name));
        assert(is.null(newpanel.icon));
        assert(is.number(newpanel.position));
        assert(is.string(newpanel.displaytype));
        assert(is.array(newpanel.items));
        assert(is.equal(1, newpanel.items.length), 'must have one property');
        item = newpanel.items[0];
        assert(is.equal(0, item.position), 'position must be 0');
        assert(is.equal(12, item.property_id), 'the property id must be: 12');

        assert(is.equal(newpanel.type_id, global.typeId));
        assert(is.equal(newpanel.name, 'mynewpanel'));
        assert(is.equal(newpanel.displaytype, 'default'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('delete the new panel', function (done) {
    request
      .delete('/v1/display/type/panels/' + global.newPanelId)
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

  it('check if the 2 properties are in the default panel and second panel deleted', function (done) {
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
        assert(is.equal(panel.type_id, global.typeId));
        assert(is.equal(panel.name, 'Default'));
        assert(is.equal(panel.displaytype, 'default'));
        assert(is.array(panel.items));
        assert(is.equal(2, panel.items.length), 'must have 2 properties');
        let item = panel.items[0];
        assert(is.equal(0, item.position), 'position must be 0');
        assert(is.equal(10, item.property_id), 'the property id must be: 10');
        item = panel.items[1];
        assert(is.equal(1, item.position), 'position must be 1');
        assert(is.equal(12, item.property_id), 'the property id must be: 12');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
