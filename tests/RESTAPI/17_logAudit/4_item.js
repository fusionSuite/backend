const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

describe('logAudit | log item actions', function() {
  it('truncate audits database table', function(done) {
    requestDB
    .get('/truncate/audits')
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a item', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'myitem1',
      type_id: 3
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.myitem1 = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a item with wrong values', function(done) {
    request
    .post('/v1/items')
    .send({
      name: 'myitem1',
      type_id: 'testformyitemone'
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('view a item', function(done) {
    request
    .get('/v1/items/'+global.myitem1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.equal('myitem1', response.body.name));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('view a item does not exists', function(done) {
    request
    .get('/v1/items/300000')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(404)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('update a item', function(done) {
    request
    .patch('/v1/items/'+global.myitem1)
    .send({
      name: 'test patch'
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('update a item with error', function(done) {
    request
    .patch('/v1/items/'+global.myitem1)
    .send({
      name: true
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('softdelete an item', function(done) {
    request
    .delete('/v1/items/'+global.myitem1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('restore an item', function(done) {
    request
    .patch('/v1/items/'+global.myitem1)
    .send({
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('softdelete an item', function(done) {
    request
    .delete('/v1/items/'+global.myitem1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('delete an item', function(done) {
    request
    .delete('/v1/items/'+global.myitem1)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('delete an item does not exists', function(done) {
    request
    .delete('/v1/items/30000000')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(404)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('verify audits added', function(done) {
    request
    .get('/v1/log/audits')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body));
      assert(is.equal(10, response.body.length));

      createItem = response.body[0];
      assert(is.equal(200, createItem.httpcode));
      assert(is.equal('CREATE', createItem.action));
      assert(is.equal('', createItem.message));

      failCreateItem = response.body[1];
      assert(is.equal(400, failCreateItem.httpcode));
      // assert(is.equal('CREATE', failCreateItem.action));
      assert(is.equal('The Type id is not valid type, The Type id must be integer', failCreateItem.message));

      viewNotexistsItem = response.body[2];
      assert(is.equal(404, viewNotexistsItem.httpcode));
      // assert(is.equal('VIEW', viewNotexistsItem.action));
      assert(is.equal('This item has not be found', viewNotexistsItem.message));

      updateItem = response.body[3];
      assert(is.equal(200, updateItem.httpcode));
      assert(is.equal('UPDATE', updateItem.action));
      assert(is.equal('', updateItem.message));

      failUpdateItem = response.body[4];
      assert(is.equal(400, failUpdateItem.httpcode));
      // assert(is.equal('UPDATE', failUpdateItem.action));
      assert(is.equal('The Name is not valid type', failUpdateItem.message));

      softdeleteItem = response.body[5];
      assert(is.equal(200, softdeleteItem.httpcode));
      assert(is.equal('SOFTDELETE', softdeleteItem.action));
      assert(is.equal('', softdeleteItem.message));

      restoreItem = response.body[6];
      assert(is.equal(200, restoreItem.httpcode));
      assert(is.equal('SOFTDELETE', restoreItem.action));
      assert(is.equal('restore', restoreItem.message));

      softdeleteItemBis = response.body[7];
      assert(is.equal(200, softdeleteItemBis.httpcode));
      assert(is.equal('SOFTDELETE', softdeleteItemBis.action));
      assert(is.equal('', softdeleteItemBis.message));

      deleteItem = response.body[8];
      assert(is.equal(200, deleteItem.httpcode));
      assert(is.equal('DELETE', deleteItem.action));
      assert(is.equal('', deleteItem.message));

      faildeleteItem = response.body[9];
      assert(is.equal(404, faildeleteItem.httpcode));
      // assert(is.equal('DELETE', faildeleteItem.action));
      assert(is.equal('The item has not be found', faildeleteItem.message));

    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

});