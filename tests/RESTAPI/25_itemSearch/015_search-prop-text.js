const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('itemSearch | search | property | text', function () {
  it('property is `Lorem ipsum`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '=Lorem ipsum')
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

  it('property is `Squadron42`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '=Squadron42')
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

  it('property integer is `...` (text too long for the webserver', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '=Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Vestibulum lorem sed risus ultricies tristique. Tempus quam pellentesque nec nam. Mauris pellentesque pulvinar pellentesque habitant morbi tristique senectus et. Risus at ultrices mi tempus imperdiet nulla malesuada pellentesque. Amet consectetur adipiscing elit pellentesque habitant morbi. In dictum non consectetur a erat. Diam vulputate ut pharetra sit amet aliquam id diam. Laoreet sit amet cursus sit amet dictum sit amet justo. Pretium aenean pharetra magna ac placerat vestibulum. Elit scelerisque mauris pellentesque pulvinar pellentesque habitant morbi. In nisl nisi scelerisque eu ultrices vitae auctor. Erat nam at lectus urna duis convallis.Quis eleifend quam adipiscing vitae. Gravida dictum fusce ut placerat orci nulla pellentesque dignissim enim. Facilisis sed odio morbi quis commodo. Nulla facilisi nullam vehicula ipsum a arcu cursus vitae. Maecenas ultricies mi eget mauris pharetra et ultrices neque ornare. In aliquam sem fringilla ut morbi tincidunt. Quis enim lobortis scelerisque fermentum dui faucibus in ornare. Cras sed felis eget velit aliquet sagittis. Nisi scelerisque eu ultrices vitae auctor eu. Mauris vitae ultricies leo integer malesuada nunc vel risus commodo.Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ac placerat vestibulum lectus mauris ultrices eros in cursus. Pellentesque sit amet porttitor eget dolor morbi non arcu risus. A scelerisque purus semper eget. Orci sagittis eu volutpat odio facilisis mauris sit. Egestas integer eget aliquet nibh praesent. At varius vel pharetra vel turpis nunc eget lorem. Velit egestas dui id ornare arcu odio ut. Mattis aliquam faucibus purus in massa tempor nec feugiat nisl. Rhoncus mattis rhoncus urna neque viverra justo. Rhoncus aenean vel elit scelerisque mauris pellentesque pulvinar pellentesque habitant. Laoreet sit amet cursus sit amet dictum sit.Mattis aliquam faucibus purus in massa tempor nec. Mauris vitae ultricies leo integer malesuada nunc vel risus. Sodales ut etiam sit amet nisl. Ultricies mi quis hendrerit dolor magna. Est sit amet facilisis magna. Ut etiam sit amet nisl purus in mollis nunc. Faucibus scelerisque eleifend donec pretium vulputate sapien nec sagittis aliquam. Aenean sed adipiscing diam donec adipiscing tristique risus. Molestie nunc non blandit massa enim nec. Dictum varius duis at consectetur lorem donec. Cursus eget nunc scelerisque viverra mauris in aliquam sem. Ut tortor pretium viverra suspendisse potenti nullam ac tortor vitae. Erat velit scelerisque in dictum non. Sit amet mattis vulputate enim nulla aliquet porttitor. Id leo in vitae turpis massa sed elementum. Morbi tincidunt augue interdum velit. Lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit. Tempus imperdiet nulla malesuada pellentesque. Fringilla urna porttitor rhoncus dolor purus non enim praesent elementum. Magna ac placerat vestibulum lectus mauris ultrices eros.Purus gravida quis blandit turpis cursus in. Suspendisse in est ante in. Ut placerat orci nulla pellentesque dignissim enim sit amet. Tristique senectus et netus et malesuada. Lacinia at quis risus sed vulputate odio ut enim blandit. Pretium aenean pharetra magna ac placerat. Ultricies lacus sed turpis tincidunt id aliquet risus feugiat in. Amet facilisis magna etiam tempor orci. Felis imperdiet proin fermentum leo. Ultricies tristique nulla aliquet enim tortor. Diam in arcu cursus euismod quis viverra nibh cras pulvinar. Enim eu turpis egestas pretium aenean pharetra.')
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
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '=null')
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

  it('property in `[Lorem ipsum,In est ante in nibh mauris]`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + "_in=Lorem ipsum,In est ante in nibh mauris")
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

  it('property contains `rem`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_contains=rem')
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

  it('property contains `4`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_contains=4')
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

  it('property begin `Lorem`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_begin=Lorem')
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

  it('property begin `lorem`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_begin=lorem')
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

  it('property begin `j`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_begin=j')
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

  it('property begin `ipsum`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_begin=ipsum')
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

  it('property end `.`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_end=.')
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
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_end=5')
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

  it('property not `In est ante in nibh mauris`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_not=In est ante in nibh mauris')
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

  it('property not `n est ante in nibh mauris`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_not=n est ante in nibh mauris')
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

  it('property not `In est ante in nibh mauris` and not `Lorem ipsum`', function (done) {
    request
      .get('/v1/items/type/' + global.typeId + '?property' + global.properties.text + '_not[]=In est ante in nibh mauris&property' + global.properties.text + '_not[]=Lorem ipsum')
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
});
