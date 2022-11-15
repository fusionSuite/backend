const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Endpoint /v1/status', function () {
  it('respond with json containing the status of the backend', function (done) {
    request
      .get('/v1/status')
      .set('Accept', 'application/json')
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
