const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('organizations | properties | delete properties', function () {
  it('delete the myprop1 (soft)', function (done) {
    request
      .delete('/v1/config/properties/' + global.myprop1)
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

  it('delete the myprop1 (hard)', function (done) {
    request
      .delete('/v1/config/properties/' + global.myprop1)
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

  it('delete the myprop2 (soft)', function (done) {
    request
      .delete('/v1/config/properties/' + global.myprop2)
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

  it('delete the myprop2 (hard)', function (done) {
    request
      .delete('/v1/config/properties/' + global.myprop2)
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

  it('delete the mypropSub1 (soft)', function (done) {
    request
      .delete('/v1/config/properties/' + global.mypropSub1)
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

  it('delete the mypropSub1 (hard)', function (done) {
    request
      .delete('/v1/config/properties/' + global.mypropSub1)
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
