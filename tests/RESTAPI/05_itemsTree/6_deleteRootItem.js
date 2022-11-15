const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemsTree | delete root item', function () {
  it('Soft delete the root item', function (done) {
    request
      .delete('/v1/items/' + global.itemLevel1Id.toString())
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
  it('Hard delete the root item', function (done) {
    request
      .delete('/v1/items/' + global.itemLevel1Id.toString())
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
