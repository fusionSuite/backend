const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('itemsHeaders | GET all items per page of 2 items', function() {
  it('Get the first page', function(done) {
    request
    .get('/v1/items/type/2?per_page=2')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect('X-Total-Count', '15')
    .expect('Content-Range', 'items 1-2/15')
    .expect('Link', '<http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=2>; rel="next", <http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=8>; rel="last"')
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body));
      
      assert(is.equal(2, response.body.length));

      firstElement = response.body[0];
      secondElement = response.body[1];
      assert(is.string(firstElement.name));
      assert(is.equal('Laptop1', firstElement.name));
      assert(is.string(secondElement.name));
      assert(is.equal('Laptop2', secondElement.name));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('Get the second page', function(done) {
    request
    .get('/v1/items/type/2?per_page=2&page=2')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect('X-Total-Count', '15')
    .expect('Content-Range', 'items 3-4/15')
    .expect('Link', '<http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=3>; rel="next", <http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=8>; rel="last", <http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=1>; rel="first", <http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=1>; rel="prev"')
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body));
      
      assert(is.equal(2, response.body.length));

      firstElement = response.body[0];
      secondElement = response.body[1];
      assert(is.string(firstElement.name));
      assert(is.equal('Laptop3', firstElement.name));
      assert(is.string(secondElement.name));
      assert(is.equal('Laptop4', secondElement.name));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('Get the third page', function(done) {
    request
    .get('/v1/items/type/2?per_page=2&page=3')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect('X-Total-Count', '15')
    .expect('Content-Range', 'items 5-6/15')
    .expect('Link', '<http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=4>; rel="next", <http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=8>; rel="last", <http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=1>; rel="first", <http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=2>; rel="prev"')
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body));
      
      assert(is.equal(2, response.body.length));

      firstElement = response.body[0];
      secondElement = response.body[1];
      assert(is.string(firstElement.name));
      assert(is.equal('Laptop5', firstElement.name));
      assert(is.string(secondElement.name));
      assert(is.equal('Laptop6', secondElement.name));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('Get the last page', function(done) {
    request
    .get('/v1/items/type/2?per_page=2&page=8')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect('X-Total-Count', '15')
    .expect('Content-Range', 'items 15-15/15')
    .expect('Link', '<http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=1>; rel="first", <http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=7>; rel="prev"')
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body));
      
      assert(is.equal(1, response.body.length));

      firstElement = response.body[0];
      assert(is.string(firstElement.name));
      assert(is.equal('Laptop15', firstElement.name));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('Return to first page with link', function(done) {
    request
    .get('/v1/items/type/2?per_page=2')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect('X-Total-Count', '15')
    .expect('Content-Range', 'items 1-2/15')
    .expect('Link', '<http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=2>; rel="next", <http://127.0.0.1/fusionsuite/backend/v1/items/type/2?per_page=2&page=8>; rel="last"')
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body));
      
      assert(is.equal(2, response.body.length));

      firstElement = response.body[0];
      secondElement = response.body[1];
      assert(is.string(firstElement.name));
      assert(is.equal('Laptop1', firstElement.name));
      assert(is.string(secondElement.name));
      assert(is.equal('Laptop2', secondElement.name));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });

  });


  global.itemsId = [];
  it('Get all items', function(done) {
    request
    .get('/v1/items/type/2')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body));
      assert(is.array(response.body));
      global.itemsId = [];
      for (let item of response.body) {
        global.itemsId.push(item.id);
      }
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });  
});
