const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemsHeaders | Delete all items', function () {
  it('soft delete the item', function (done) {
    for (const id of global.itemsId) {
      request
        .delete('/v1/items/' + id)
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect('Content-Type', /json/)
        .expect(200)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
        });
    }
    return done();
  });

  it('permanently delete the item', function (done) {
    for (const id of global.itemsId) {
      request
        .delete('/v1/items/' + id)
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect('Content-Type', /json/)
        .expect(200)
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
        });
    }
    return done();
  });
});
