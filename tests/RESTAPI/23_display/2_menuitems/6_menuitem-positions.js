const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('Display menuitem | Manage the position', function () {
  it('create a new item 01', function (done) {
    request
      .post('/v1/display/menu/item')
      .send({
        name: 'Laptop',
        type_id: 3,
        menu_id: global.menu01id,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.item01id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new item 02', function (done) {
    request
      .post('/v1/display/menu/item')
      .send({
        name: 'Laptop 2',
        type_id: 4,
        menu_id: global.menu01id,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.item02id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get list of items and check position 0 and position 1', function (done) {
    request
      .get('/v1/display/menu/item')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(response.body.length, 2), 'must have 2 items');
        const menu01 = response.body[0];
        const menu02 = response.body[1];
        assert(is.equal(menu01.name, 'Laptop'));
        assert(is.equal(menu01.position, 0));
        assert(is.equal(menu02.name, 'Laptop 2'));
        assert(is.equal(menu02.position, 1));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new item 01b', function (done) {
    request
      .post('/v1/display/menu/item')
      .send({
        name: 'Laptop 1b',
        type_id: 5,
        menu_id: global.menu01id,
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
        global.item01bid = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get list of items and check position 0, 1 and 2', function (done) {
    request
      .get('/v1/display/menu/item')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(response.body.length, 3), 'must have 3 items');
        const menu01 = response.body[0];
        const menu02 = response.body[1];
        const menu01b = response.body[2];
        assert(is.equal(menu01.name, 'Laptop'));
        assert(is.equal(menu01.position, 0));
        assert(is.equal(menu02.name, 'Laptop 2'));
        assert(is.equal(menu02.position, 2));
        assert(is.equal(menu01b.name, 'Laptop 1b'));
        assert(is.equal(menu01b.position, 1));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new item 02-1', function (done) {
    request
      .post('/v1/display/menu/item')
      .send({
        name: 'Laptop 02-1',
        type_id: 7,
        menu_id: global.menu02id,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.item02_1id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new item 02-2', function (done) {
    request
      .post('/v1/display/menu/item')
      .send({
        name: 'Laptop 02-2',
        type_id: 8,
        menu_id: global.menu02id,
        position: 0,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));

        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.item02_2id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new item 03', function (done) {
    request
      .post('/v1/display/menu/item')
      .send({
        name: 'Laptop 3',
        type_id: 6,
        menu_id: global.menu01id,
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
        global.item03id = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('get list of items and check position of the 6 items', function (done) {
    request
      .get('/v1/display/menu/item')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(response.body.length, 6), 'must have 6 items');
        const menu01 = response.body[0];
        const menu02 = response.body[1];
        const menu01b = response.body[2];
        const menu03 = response.body[5];
        assert(is.equal(menu01.name, 'Laptop'));
        assert(is.equal(menu01.position, 0));
        assert(is.equal(menu02.name, 'Laptop 2'));
        assert(is.equal(menu02.position, 2));
        assert(is.equal(menu01b.name, 'Laptop 1b'));
        assert(is.equal(menu01b.position, 1));
        assert(is.equal(menu03.name, 'Laptop 3'));
        assert(is.equal(menu03.position, 3));

        const menu21 = response.body[3];
        const menu22 = response.body[4];
        assert(is.equal(menu21.name, 'Laptop 02-1'));
        assert(is.equal(menu21.position, 1));
        assert(is.equal(menu22.name, 'Laptop 02-2'));
        assert(is.equal(menu22.position, 0));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('delete the item Laptop 1b', function (done) {
    request
      .delete('/v1/display/menu/item/' + global.item01bid)
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

  it('get list of items and check position of the 5 items', function (done) {
    request
      .get('/v1/display/menu/item')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(response.body.length, 5), 'must have 5 items');
        const menu01 = response.body[0];
        const menu02 = response.body[1];
        const menu03 = response.body[4];
        assert(is.equal(menu01.name, 'Laptop'));
        assert(is.equal(menu01.position, 0));
        assert(is.equal(menu02.name, 'Laptop 2'));
        assert(is.equal(menu02.position, 1));
        assert(is.equal(menu03.name, 'Laptop 3'));
        assert(is.equal(menu03.position, 2));

        const menu21 = response.body[2];
        const menu22 = response.body[3];
        assert(is.equal(menu21.name, 'Laptop 02-1'));
        assert(is.equal(menu21.position, 1));
        assert(is.equal(menu22.name, 'Laptop 02-2'));
        assert(is.equal(menu22.position, 0));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('delete the item Laptop 1', function (done) {
    request
      .delete('/v1/display/menu/item/' + global.item01id)
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

  it('delete the item Laptop 2', function (done) {
    request
      .delete('/v1/display/menu/item/' + global.item02id)
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

  it('delete the item Laptop 3', function (done) {
    request
      .delete('/v1/display/menu/item/' + global.item03id)
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

  it('delete the item 02-1', function (done) {
    request
      .delete('/v1/display/menu/item/' + global.item02_1id)
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

  it('delete the item 02-2', function (done) {
    request
      .delete('/v1/display/menu/item/' + global.item02_2id)
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

  it('delete the menu 01', function (done) {
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

  it('delete the menu 02', function (done) {
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
});
