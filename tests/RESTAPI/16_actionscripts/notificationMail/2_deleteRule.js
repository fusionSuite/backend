const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Delete the rule', function () {
  it('it delete a rule', function (done) {
    request
      .delete('/v1/rules/actionscript/' + global.ruleId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
