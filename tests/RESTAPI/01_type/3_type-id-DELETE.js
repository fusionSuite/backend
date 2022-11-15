const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('type | Delete /v1/config/types/:id', function () {
  it('soft delete the Firewall type', function (done) {
    request
      .delete('/v1/config/types/' + global.id)
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
      .delete('/v1/config/types/' + global.id)
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
