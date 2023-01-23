const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display typepanelitem | get panelitems', function () {
  it('patch panelitem for position', function (done) {
    request
      .patch('/v1/display/type/panelitems/' + global.panelitem2id)
      .send({
        position: 2,
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

  it('get the first panelitem', function (done) {
    request
      .get('/v1/display/type/panelitems/' + global.panelitem1id)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 6), 'must have 6 properties');
        assert(is.equal(response.body.property_id, 10), 'property_id must be 10');
        assert(is.equal(response.body.position, 0), 'position must be to 0');
        assert(is.equal(response.body.typepanel_id, global.defaultpanelId), 'typepanel_id not tight');
        assert(is.null(response.body.timeline_message), 'timelinemessage must be null');
        assert(is.equal(response.body.timeline_options, '[]'), 'timeline_options must be "[]"');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get the second panelitem', function (done) {
    request
      .get('/v1/display/type/panelitems/' + global.panelitem2id)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 6), 'must have 6 properties');
        assert(is.equal(response.body.property_id, 12), 'property_id must be 12');
        assert(is.equal(response.body.position, 2), 'position must be to 1');
        assert(is.equal(response.body.typepanel_id, global.defaultpanelId), 'typepanel_id not tight');
        assert(is.null(response.body.timeline_message), 'timelinemessage must be null');
        assert(is.equal(response.body.timeline_options, '[]'), 'timeline_options must be "[]"');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get the third panelitem', function (done) {
    request
      .get('/v1/display/type/panelitems/' + global.panelitem3id)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 6), 'must have 6 properties');
        assert(is.equal(response.body.property_id, 13), 'property_id must be 13');
        assert(is.equal(response.body.position, 1), 'position must be to 2');
        assert(is.equal(response.body.typepanel_id, global.defaultpanelId), 'typepanel_id not tight');
        assert(is.null(response.body.timeline_message), 'timelinemessage must be null');
        assert(is.equal(response.body.timeline_options, '[]'), 'timeline_options must be "[]"');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
