const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemPropertyWhenAddPropertyToType | delete type', function () {
  it('Soft delete the type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeId.toString())
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

  it('Hard delete the type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeId.toString())
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
