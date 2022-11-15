const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('usereventTimestamp | delete property', function () {
  it('soft delete the property', function (done) {
    request
      .delete('/v1/config/properties/' + global.propertyId)
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

  it('hard delete the property', function (done) {
    request
      .delete('/v1/config/properties/' + global.propertyId)
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
