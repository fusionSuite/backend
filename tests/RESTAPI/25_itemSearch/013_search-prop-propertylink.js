const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemSearch | search | property | propertylink', function () {
  it('property is the id of first name', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '=1')
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

  it('property is `null`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '=null')
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

  it('property in `[xx, yy]`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_in=1,7')
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

  it('property contains `t`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_contains=t')
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

  it('property contains `a`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_contains=a')
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

  it('property begin `first`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_begin=first')
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

  it('property begin `au`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_begin=au')
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

  it('property begin `-`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_begin=-')
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

  it('property end `ame`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_end=ame')
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

  it('property end `ome`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_end=yo')
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

  it('property not `first name`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_not=1')
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

  it('property not `first name` and not `address`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_not[]=1&property' + global.properties.propertylink + '_not[]=7')
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

  it('property not `null`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.propertylink + '_not=null')
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
});
