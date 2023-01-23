const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display menuitem | Delete menuitem', function () {
  it('delete the menu item', function (done) {
    request
      .delete('/v1/display/menu/item/' + global.id)
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
});
