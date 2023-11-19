const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemSearch | search | property | datetime', function () {
  it('property is `2018-05-26 10:56:34`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '=2018-05-26 10:56:34')
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
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '=null')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body));
        assert(is.equal(3, response.body.length));
        assert(is.equal('all is null', response.body[0].name));
        assert(is.equal('myitem001', response.body[1].name));
        assert(is.equal('myitem004,ok', response.body[2].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('property in `[2018-05-26 10:56:34, 1998-01-01 00:00:01]`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_in=2018-05-26 10:56:34,1998-01-01 00:00:01')
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

  it('property contains `-05`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_contains=-05')
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

  it('property contains `8`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_contains=8')
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

  it('property begin `19`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_begin=19')
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

  it('property begin `3`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_begin=3')
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
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_end=4')
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

  it('property end `5`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_end=5')
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

  it('property before `2056-12-12 00:00:00`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_before=2056-12-12 00:00:00')
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

  it('property before `2018-15-26 00:00:00`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_before=2018-05-24 00:00:00')
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

  it('property before `2018-05-26 11:00:00`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_before=2018-05-26 11:00:00')
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

  it('property before `2018-15-26 10:00:00`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_before=2018-05-24 10:00:00')
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

  it('property after `2056-12-13 00:00:00`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_after=2056-12-13 00:00:00')
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

  it('property after `2000-01-01 00:00:00`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_after=2000-01-01 00:00:00')
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

  it('property not `2018-05-26 00:00:00`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_not=2018-05-26 00:00:00')
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

  it('property not `2018-05-26 10:56:34`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_not=2018-05-26 10:56:34')
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

  it('property not `2018-05-26 10:56:34` and not `1998-01-01 00:00:01`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.datetime + '_not[]=2018-05-26 10:56:34&property' + global.properties.datetime + '_not[]=1998-01-01 00:00:01')
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
});
