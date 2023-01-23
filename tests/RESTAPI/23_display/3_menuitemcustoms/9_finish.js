const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display menuitemcustom | delete all', function () {
  it('delete a menu', function (done) {
    request
      .delete('/v1/display/menu/' + global.menu01id)
      .send({ name: 'Assets 01' })
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
});
