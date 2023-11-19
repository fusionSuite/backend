const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemSearch | search | property | list', function () {
  it('property is `42`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '=' + global.listvalues.myitemlist1)
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
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '=null')
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
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '_in=' + global.listvalues.myitemlist1 + ',' + global.listvalues.itemlist11)
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

  it('property contains `st1`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '_contains=st1')
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

  it('property begin `my`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '_begin=my')
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

  it('property begin `au`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '_begin=au')
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
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '_begin=-')
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

  it('property end `11`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '_end=11')
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

  it('property end `yo`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '_end=yo')
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

  it('property not `myitemlist1`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '_not=' + global.listvalues.myitemlist1)
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

  it('property not `myitemlist1` and not `itemlist11`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '_not[]=' + global.listvalues.myitemlist1 + '&property' + global.properties.list + '_not[]=' + global.listvalues.itemlist11)
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

  it('property not `null`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.list + '_not=null')
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
