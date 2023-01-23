const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display menu | Manage the position', function () {
  it('create a new menu 01', function (done) {
    request
      .post('/v1/display/menu')
      .send({ name: 'Assets' })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.menu01id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new menu 02', function (done) {
    request
      .post('/v1/display/menu')
      .send({ name: 'Assets 2' })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.menu02id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get list of menus and check position 0 and position 1', function (done) {
    request
      .get('/v1/display/menu')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(response.body.length, 2), 'must have 2 menus');
        const menu01 = response.body[0];
        const menu02 = response.body[1];
        assert(is.equal(menu01.name, 'Assets'));
        assert(is.equal(menu01.position, 0));
        assert(is.equal(menu02.name, 'Assets 2'));
        assert(is.equal(menu02.position, 1));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new menu 01b', function (done) {
    request
      .post('/v1/display/menu')
      .send({
        name: 'Assets 1b',
        position: 1,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.menu01bid = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get list of menus and check position 0, 1 and 2', function (done) {
    request
      .get('/v1/display/menu')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(response.body.length, 3), 'must have 3 menus');
        const menu01 = response.body[0];
        const menu02 = response.body[1];
        const menu01b = response.body[2];
        assert(is.equal(menu01.name, 'Assets'));
        assert(is.equal(menu01.position, 0));
        assert(is.equal(menu02.name, 'Assets 2'));
        assert(is.equal(menu02.position, 2));
        assert(is.equal(menu01b.name, 'Assets 1b'));
        assert(is.equal(menu01b.position, 1));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new menu 03', function (done) {
    request
      .post('/v1/display/menu')
      .send({
        name: 'Assets 3',
        position: 10,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.menu03id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get list of menus and check position of the 4 menus', function (done) {
    request
      .get('/v1/display/menu')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(response.body.length, 4), 'must have 4 menus');
        const menu01 = response.body[0];
        const menu02 = response.body[1];
        const menu01b = response.body[2];
        const menu03 = response.body[3];
        assert(is.equal(menu01.name, 'Assets'));
        assert(is.equal(menu01.position, 0));
        assert(is.equal(menu02.name, 'Assets 2'));
        assert(is.equal(menu02.position, 2));
        assert(is.equal(menu01b.name, 'Assets 1b'));
        assert(is.equal(menu01b.position, 1));
        assert(is.equal(menu03.name, 'Assets 3'));
        assert(is.equal(menu03.position, 3));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('delete the Assets menu 1b', function (done) {
    request
      .delete('/v1/display/menu/' + global.menu01bid)
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

  it('get list of menus and check position of the 3 menus', function (done) {
    request
      .get('/v1/display/menu')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(response.body.length, 3), 'must have 3 menus');
        const menu01 = response.body[0];
        const menu02 = response.body[1];
        const menu03 = response.body[2];
        assert(is.equal(menu01.name, 'Assets'));
        assert(is.equal(menu01.position, 0));
        assert(is.equal(menu02.name, 'Assets 2'));
        assert(is.equal(menu02.position, 1));
        assert(is.equal(menu03.name, 'Assets 3'));
        assert(is.equal(menu03.position, 2));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('delete the Assets menu 1', function (done) {
    request
      .delete('/v1/display/menu/' + global.menu01id)
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

  it('delete the Assets menu 2', function (done) {
    request
      .delete('/v1/display/menu/' + global.menu02id)
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

  it('delete the Assets menu 3', function (done) {
    request
      .delete('/v1/display/menu/' + global.menu03id)
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
