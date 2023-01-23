const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display menu | Delete menu', function () {
  it('delete the Assets menu', function (done) {
    request
      .delete('/v1/display/menu/' + global.id)
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
