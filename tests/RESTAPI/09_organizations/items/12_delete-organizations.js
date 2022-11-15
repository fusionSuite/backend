const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('organizations | items | delete organizations', function () {
  it('Soft delete the subOrg1', function (done) {
    request
      .delete('/v1/items/' + global.subOrg1.toString())
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

  it('Hard delete the subOrg1', function (done) {
    request
      .delete('/v1/items/' + global.subOrg1.toString())
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

  it('Soft delete the subOrg2', function (done) {
    request
      .delete('/v1/items/' + global.subOrg2.toString())
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

  it('Hard delete the subOrg2', function (done) {
    request
      .delete('/v1/items/' + global.subOrg2.toString())
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
