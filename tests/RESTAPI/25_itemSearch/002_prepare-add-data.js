const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemSearch | prepare data', function () {
  // eslint-disable-next-line mocha/no-setup-in-describe
  global.dataProvider.forEach((data) => {
    it('create an item: ' + data.name, function (done) {
      const itemData = {
        name: data.name,
        type_id: global.typeId,
        properties: [
        ],
      };
      for (let [valuetype, value] of Object.entries(data.properties)) {
        if (valuetype === 'list' && value !== null) {
          value = global.listvalues[value];
        }
        if (valuetype === 'itemlink' && value !== null) {
          value = global[value];
        }
        if (valuetype === 'itemlinks' && value !== null) {
          const values = [];
          for (const val of value) {
            values.push(global[val]);
          }
          value = values;
        }

        itemData.properties.push(
          {
            property_id: global.properties[valuetype],
            value,
          },
        );
      }
      request
        .post('/v1/items')
        .send(itemData)
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });
  });
});
