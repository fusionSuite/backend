const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('Endpoint /v1/items', function() {

  it('Get serial number property id', function(done) {
    request
    .get('/v1/config/typeproperties')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      response.body.forEach(property => {
        if (property.internalname === 'serialnumber') {
          global.propertyid = property.id;
        }
      });

    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
  
  it('create a new item', function(done) {
    request
    .post('/v1/items')
    .send({name: 'L0014',type_id: 2,properties:[{property_id:global.propertyid,value:"serialxxxxxx"}]})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));

      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.id = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new item, but forget name => error', function(done) {
    request
    .post('/v1/items')
    .send({type_id: 2})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Name is required'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new type, but name not in right type => error', function(done) {
    request
    .post('/v1/items')
    .send({name: true, type_id: 2})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Name is not valid type'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new type, but properties.property_id not defined => error', function(done) {
    request
    .post('/v1/items')
    .send({name: 'L0014',type_id: 2,properties:[{propertyId:3,value:"serialxxxxxx"}]})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Property id is required'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new type, but properties.property_id is defined to 0 => error', function(done) {
    request
    .post('/v1/items')
    .send({name: 'L0014',type_id: 2,properties:[{property_id:0,value:"serialxxxxxx"}]})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Property id minimum is 1'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new type, but properties.property_id is a string => error', function(done) {
    request
    .post('/v1/items')
    .send({name: 'L0014',type_id: 2,properties:[{property_id:"3",value:"serialxxxxxx"}]})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Property id is not valid type'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new type, but properties.value not defined => error', function(done) {
    request
    .post('/v1/items')
    .send({name: 'L0014',type_id: 2,properties:[{property_id:3}]})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Value must be present'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new type, but properties.value is an integer => error', function(done) {
    request
    .post('/v1/items')
    .send({name: 'L0014',type_id: 2,properties:[{property_id:3,value:1000}]})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, 'The Value is not valid type'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new item with properties field but empty', function(done) {
    request
    .post('/v1/items')
    .send({name: 'L0014',type_id: 2,properties:[]})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));

      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create laptops with random names and serials, also in different langs', function(done) {
    // Generate random
    for (var i=1;i<=60; i++) {
      if (i < 10) {
        faker.setLocale('en');
      } else if (i < 20) {
        faker.setLocale('ar');
      } else if (i < 30) {
        faker.setLocale('fr');
      } else if (i < 40) {
        faker.setLocale('ja');
      } else if (i < 50) {
        faker.setLocale('ru');
      } else if (i < 60) {
        faker.setLocale('zh_CN');
      }
      let name = faker.random.word();
      let serial = faker.random.word() + faker.datatype.number();
      let myId = 0;
      request
      .post('/v1/items')
      .send({name: name,type_id: 2,properties:[{property_id:global.propertyid,value:serial}]})
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        myId = response.body.id;

        // Test get it
        request
        .get('/v1/items/'+myId)
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function(response) {
          assert(is.not.empty(response.body));
          assert(is.equal(name, response.body.name));
          assert(is.equal(serial, response.body.properties[0].value));
        })
        .end(function(err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
        });
      })
      .end(function(err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
      });
    }
    return done();
  });
});
