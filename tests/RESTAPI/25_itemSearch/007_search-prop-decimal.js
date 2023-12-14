const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemSearch | search | property | decimal', function () {
  it('property is `3.1`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '=3.1')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(1, response.body.length));
        assert(is.equal('myitem002', response.body[0].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property is `null`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '=null')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(2, response.body.length));
        assert(is.equal('all is null', response.body[0].name));
        assert(is.equal('myitem004,ok', response.body[1].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property in `[3.1, 5.6]`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_in=3.1,5.6')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(2, response.body.length));
        assert(is.equal('myitem002', response.body[0].name));
        assert(is.equal('myitem003', response.body[1].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property contains `3`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_contains=3')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(2, response.body.length));
        assert(is.equal('myitem001', response.body[0].name));
        assert(is.equal('myitem002', response.body[1].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property contains `.`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_contains=.')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(3, response.body.length));
        assert(is.equal('myitem001', response.body[0].name));
        assert(is.equal('myitem002', response.body[1].name));
        assert(is.equal('myitem003', response.body[2].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property contains `0`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_contains=0')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(0, response.body.length));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property begin `3`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_begin=3')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(2, response.body.length));
        assert(is.equal('myitem001', response.body[0].name));
        assert(is.equal('myitem002', response.body[1].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property begin `.8`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_begin=8')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(0, response.body.length));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property end `4`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_end=4')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(1, response.body.length));
        assert(is.equal('myitem001', response.body[0].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property end `5`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_end=5')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(0, response.body.length));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property less `3.2`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_less=3.2')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(2, response.body.length));
        assert(is.equal('myitem001', response.body[0].name));
        assert(is.equal('myitem002', response.body[1].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property less `3.14`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_less=3.14')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(1, response.body.length));
        assert(is.equal('myitem002', response.body[0].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property greater `6.0`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_greater=6.0')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(0, response.body.length));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property greater `5.0`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_greater=5.0')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(1, response.body.length));
        assert(is.equal('myitem003', response.body[0].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property not `3.1`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_not=3.1')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(2, response.body.length));
        assert(is.equal('myitem001', response.body[0].name));
        assert(is.equal('myitem003', response.body[1].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property not `3.14` and not `3.1`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.decimal + '_not[]=3.1&property' + global.properties.decimal + '_not[]=3.14')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(1, response.body.length));
        assert(is.equal('myitem003', response.body[0].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
