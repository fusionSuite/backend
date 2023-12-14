const supertest = require('supertest');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const deleteProperty = (done, propertyId) => {
  request
    .delete('/v1/config/properties/' + propertyId)
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
};

const deleteItem = (done, itemId) => {
  request
    .delete('/v1/items/' + itemId)
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
};

describe('itemSearch | clean data', function (done) {
  // delete type
  it('delete type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeId)
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

  // force delete type
  it('force delete type', function (done) {
    request
      .delete('/v1/config/types/' + global.typeId)
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

  // delete properties
  it('delete property: boolean', function (done) {
    deleteProperty(done, global.properties.boolean);
  });

  it('delete property: date', function (done) {
    deleteProperty(done, global.properties.date);
  });

  it('delete property: datetime', function (done) {
    deleteProperty(done, global.properties.datetime);
  });

  it('delete property: decimal', function (done) {
    deleteProperty(done, global.properties.decimal);
  });

  it('delete property: integer', function (done) {
    deleteProperty(done, global.properties.integer);
  });

  it('delete property: itemlink', function (done) {
    deleteProperty(done, global.properties.itemlink);
  });

  it('delete property: itemlinks', function (done) {
    deleteProperty(done, global.properties.itemlinks);
  });

  it('delete property: list', function (done) {
    deleteProperty(done, global.properties.list);
  });

  it('delete property: number', function (done) {
    deleteProperty(done, global.properties.number);
  });

  it('delete property: propertylink', function (done) {
    deleteProperty(done, global.properties.propertylink);
  });

  it('delete property: string', function (done) {
    deleteProperty(done, global.properties.string);
  });

  it('delete property: text', function (done) {
    deleteProperty(done, global.properties.text);
  });

  it('delete property: time', function (done) {
    deleteProperty(done, global.properties.time);
  });

  it('delete property: typelink', function (done) {
    deleteProperty(done, global.properties.typelink);
  });

  it('delete property: typelinks', function (done) {
    deleteProperty(done, global.properties.typelinks);
  });

  // force delete properties
  it('force delete property: boolean', function (done) {
    deleteProperty(done, global.properties.boolean);
  });

  it('force delete property: date', function (done) {
    deleteProperty(done, global.properties.date);
  });

  it('force delete property: datetime', function (done) {
    deleteProperty(done, global.properties.datetime);
  });

  it('force delete property: decimal', function (done) {
    deleteProperty(done, global.properties.decimal);
  });

  it('force delete property: integer', function (done) {
    deleteProperty(done, global.properties.integer);
  });

  it('force delete property: itemlink', function (done) {
    deleteProperty(done, global.properties.itemlink);
  });

  it('force delete property: itemlinks', function (done) {
    deleteProperty(done, global.properties.itemlinks);
  });

  it('force delete property: list', function (done) {
    deleteProperty(done, global.properties.list);
  });

  it('force delete property: number', function (done) {
    deleteProperty(done, global.properties.number);
  });

  it('force delete property: propertylink', function (done) {
    deleteProperty(done, global.properties.propertylink);
  });

  it('force delete property: string', function (done) {
    deleteProperty(done, global.properties.string);
  });

  it('force delete property: text', function (done) {
    deleteProperty(done, global.properties.text);
  });

  it('force delete property: time', function (done) {
    deleteProperty(done, global.properties.time);
  });

  it('force delete property: typelink', function (done) {
    deleteProperty(done, global.properties.typelink);
  });

  it('force delete property: typelinks', function (done) {
    deleteProperty(done, global.properties.typelinks);
  });

  // Delete items (laptops and antivirus)
  it('delete item: laptop123', function (done) {
    deleteItem(done, global.laptop123);
  });

  it('delete item: laptop300', function (done) {
    deleteItem(done, global.laptop300);
  });

  it('delete item: laptop012', function (done) {
    deleteItem(done, global.laptop012);
  });

  it('delete item: antivirusAvast', function (done) {
    deleteItem(done, global.antivirusAvast);
  });

  it('delete item: antivirusAVG', function (done) {
    deleteItem(done, global.antivirusAVG);
  });

  it('force delete item: laptop123', function (done) {
    deleteItem(done, global.laptop123);
  });

  it('force delete item: laptop300', function (done) {
    deleteItem(done, global.laptop300);
  });

  it('force delete item: laptop012', function (done) {
    deleteItem(done, global.laptop012);
  });

  it('force delete item: antivirusAvast', function (done) {
    deleteItem(done, global.antivirusAvast);
  });

  it('force delete item: antivirusAVG', function (done) {
    deleteItem(done, global.antivirusAVG);
  });
});
