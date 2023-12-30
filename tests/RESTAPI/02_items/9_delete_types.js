const supertest = require('supertest');
const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('items | Endpoint /v1/items | delete type', function () {
  it('soft delete a type', function (done) {
    request
      .delete('/v1/config/types/' + global.mytypeId)
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

  it('hard delete a type', function (done) {
    request
      .delete('/v1/config/types/' + global.mytypeId)
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
