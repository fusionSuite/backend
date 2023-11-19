const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemPropertyWhenAddPropertyToTypeByTemplate | add new property to type', function () {
  it('Attach a property to the type', function (done) {
    request
      .post('/v1/config/types/templates')
      .send({
        license: [],
        types: [
          {
            name: 'testType',
            internalname: 'testtype',
            panels: [
              {
                name: 'Main',
                properties: [
                  {
                    name: 'Installation date',
                    internalname: 'installationdate',
                    valuetype: 'date',
                    regexformat: '',
                    listvalues: [],
                    unit: '',
                    default: null,
                    description: '',
                  },
                ],
              },
            ],
          },
        ],
      })
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

  it('verify the item have right the property (installation date)', function (done) {
    request
      .get('/v1/items/' + global.itemId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body), 'The body must contain something');
        assert(is.object(response.body), 'the body response must be an object');
        assert(is.equal(2, response.body.properties.length), 'must have 2 properties');
        assert(is.equal('installationdate', response.body.properties[1].internalname), 'the second property internalname must be installationdate');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
