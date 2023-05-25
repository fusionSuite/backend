const supertest = require('supertest');
const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemProperties: itemlink type | delete item', function () {
  for (let i = 1; i < 7; i++) {
    it('Soft delete the item ' + i, function (done) {
      request
        .delete('/v1/items/' + global['itemId' + i].toString())
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
    it('Hard delete the item ' + i, function (done) {
      request
        .delete('/v1/items/' + global['itemId' + i].toString())
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
  }
});
