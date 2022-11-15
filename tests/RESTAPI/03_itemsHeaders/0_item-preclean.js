const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemsHeaders | Pre clean to be sure not have items in DB /v1/items/:id', function () {
  it('Get all items', function (done) {
    global.itemsId = [];
    request
      .get('/v1/items/type/3')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        for (const item of response.body) {
          global.itemsId.push(item.id);
        }
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

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
