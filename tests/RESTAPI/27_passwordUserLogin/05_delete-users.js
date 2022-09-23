const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('password user login | delete users', function () {
  it('Soft delete the user1', function (done) {
    request
      .delete('/v1/items/' + global.user1.toString())
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

  it('Hard delete the user1', function (done) {
    request
      .delete('/v1/items/' + global.user1.toString())
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
