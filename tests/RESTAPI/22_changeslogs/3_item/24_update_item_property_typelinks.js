const supertest = require('supertest');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('changes | items | update typelinks type property', function () {
  it('add an itemlink property of the item', function (done) {
    request
      .post('/v1/items/' + global.itemId + '/property/' + global.properties.typelinks + '/typelinks')
      .send(
        {
          value: 2,
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

  it('get the items (after added) and check if changes has new entry', function (done) {
    global.changesEntries += 1;
    request
      .get('/v1/items/' + global.itemId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('laptop yyy6yy', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
        assert(is.equal(global.changesEntries, response.body.changes.length), 'must have ' + global.changesEntries + ' changes');

        const lastChanges = response.body.changes[(global.changesEntries - 1)];
        assert(is.equal('admin', lastChanges.username), 'must have username = admin');
        assert(is.null(lastChanges.old_value), 'old value is wrong');
        assert(is.equal('{"id":2,"name":"Users"}', lastChanges.new_value), 'new value is wrong');
        assert(is.equal('admin added "Users" to "property with type typelinks"', lastChanges.message), 'wrong message');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table when add property - must have only 1 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt += 1;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('delete a typelink property of the item', function (done) {
    request
      .delete('/v1/items/' + global.itemId + '/property/' + global.properties.typelinks + '/typelinks/1')
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

  it('get the items (after deleted) and check if changes has new entry', function (done) {
    global.changesEntries += 1;
    request
      .get('/v1/items/' + global.itemId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('laptop yyy6yy', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
        assert(is.equal(global.changesEntries, response.body.changes.length), 'must have ' + global.changesEntries + ' changes');

        const lastChanges = response.body.changes[(global.changesEntries - 1)];
        assert(is.equal('admin', lastChanges.username), 'must have username = admin');
        assert(is.equal('{"id":1,"name":"Organization"}', lastChanges.old_value), 'old value is wrong');
        assert(is.null(lastChanges.new_value), 'new value is wrong');
        assert(is.equal('admin deleted property "property with type typelinks" named "Organization"', lastChanges.message), 'wrong message');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table when delete property - must have only 1 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt += 1;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('Update typelinks property of the item to [1, 2]', function (done) {
    request
      .patch('/v1/items/' + global.itemId + '/property/' + global.properties.typelinks)
      .send(
        {
          value: [1, 2],
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

  it('get the item and check if changes has new entry', function (done) {
    global.changesEntries += 1;
    request
      .get('/v1/items/' + global.itemId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('laptop yyy6yy', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
        assert(is.equal(global.changesEntries, response.body.changes.length), 'must have ' + global.changesEntries + ' changes');

        const lastChanges = response.body.changes[(global.changesEntries - 1)];
        assert(is.equal('admin', lastChanges.username), 'must have username = admin');
        assert(is.null(lastChanges.old_value), 'old value is wrong');
        assert(is.equal('{"id":1,"name":"Organization"}', lastChanges.new_value), 'new value is wrong');
        assert(is.equal('admin added "Organization" to "property with type typelinks"', lastChanges.message), 'wrong message');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table when update to [1, 2] - must have only 1 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt += 1;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('Update typelinks property of the item to [2] (will delete the id 1', function (done) {
    request
      .patch('/v1/items/' + global.itemId + '/property/' + global.properties.typelinks)
      .send(
        {
          value: [2],
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

  it('get the item and check if changes has new entry after reduced items', function (done) {
    global.changesEntries += 1;
    request
      .get('/v1/items/' + global.itemId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('laptop yyy6yy', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
        assert(is.equal(global.changesEntries, response.body.changes.length), 'must have ' + global.changesEntries + ' changes');

        const lastChanges = response.body.changes[(global.changesEntries - 1)];
        assert(is.equal('admin', lastChanges.username), 'must have username = admin');
        assert(is.equal('{"id":1,"name":"Organization"}', lastChanges.old_value), 'old value is wrong');
        assert(is.null(lastChanges.new_value), 'new value is wrong');
        assert(is.equal('admin deleted property "property with type typelinks" named "Organization"', lastChanges.message), 'wrong message');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table when update to [2] - must have only 1 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt += 1;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('Update typelinks property of the item to null', function (done) {
    request
      .patch('/v1/items/' + global.itemId + '/property/' + global.properties.typelinks)
      .send(
        {
          value: null,
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

  it('get the item and check if changes has 2 new entries after null', function (done) {
    global.changesEntries += 2;
    request
      .get('/v1/items/' + global.itemId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.equal('laptop yyy6yy', response.body.name));
        assert(is.propertyDefined(response.body, 'changes'));
        assert(is.equal(global.changesEntries, response.body.changes.length), 'must have ' + global.changesEntries + ' changes');

        let lastChanges = response.body.changes[(global.changesEntries - 2)];
        assert(is.equal('admin', lastChanges.username), '(first) must have username = admin');
        assert(is.equal('{"id":2,"name":"Users"}', lastChanges.old_value), '(first) old value is wrong');
        assert(is.null(lastChanges.new_value), '(first) new value is wrong');
        assert(is.equal('admin deleted property "property with type typelinks" named "Users"', lastChanges.message), '(first) wrong message');

        lastChanges = response.body.changes[(global.changesEntries - 1)];
        assert(is.equal('admin', lastChanges.username), '(second) must have username = admin');
        assert(is.null(lastChanges.old_value), '(second) old value is wrong');
        assert(is.null(lastChanges.new_value), '(second) new value is wrong');
        assert(is.equal('admin added null to "property with type typelinks"', lastChanges.message), '(second) wrong message');
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('check changes rows in database table when update to null - must have only 2 rows more', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt += 2;
        assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });
});
