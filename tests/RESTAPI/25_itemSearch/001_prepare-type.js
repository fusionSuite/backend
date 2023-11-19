const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

global.properties = {};
global.listvalues = {};
global.listvaluesById = {};

const createProperty = (done, valuetype, defaultvalue, canbenull = true, allowedtypes = null, listvalues = null) => {
  const data = {
    name: 'Test for ' + valuetype,
    internalname: 'testfor' + valuetype,
    valuetype,
    regexformat: '',
    listvalues: [],
    default: defaultvalue,
    unit: '',
    description: 'Test of the type ' + valuetype,
  };
  if (!canbenull) {
    data.canbenull = false;
  }
  if (allowedtypes !== null) {
    data.allowedtypes = allowedtypes;
  }
  if (listvalues !== null) {
    data.listvalues = listvalues;
  }
  request
    .post('/v1/config/properties')
    .send(data)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.properties[valuetype] = response.body.id;
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const attachPropertyToType = (done, valuetype) => {
  request
    .post('/v1/config/types/' + global.typeId.toString() + '/property/' + global.properties[valuetype])
    .send()
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.array(response.body), 'the body response must be an array');
      assert(is.equal(response.body.length, 0));
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

describe('itemSearch | prepare type', function () {
  it('create a new type', function (done) {
    request
      .post('/v1/config/types')
      .send({
        name: 'testforsearch',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.typeId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create property: boolean', function (done) {
    createProperty(done, 'boolean', false);
  });

  it('attach property: boolean', function (done) {
    attachPropertyToType(done, 'boolean');
  });

  it('create property: date', function (done) {
    createProperty(done, 'date', null);
  });

  it('attach property: date', function (done) {
    attachPropertyToType(done, 'date');
  });

  it('create property: datetime', function (done) {
    createProperty(done, 'datetime', '2023-10-20 08:34:03');
  });

  it('attach property: datetime', function (done) {
    attachPropertyToType(done, 'datetime');
  });

  it('create property: decimal', function (done) {
    createProperty(done, 'decimal', 3.14);
  });

  it('attach property: decimal', function (done) {
    attachPropertyToType(done, 'decimal');
  });

  it('create property: integer', function (done) {
    createProperty(done, 'integer', 564);
  });

  it('attach property: integer', function (done) {
    attachPropertyToType(done, 'integer');
  });

  it('create property: itemlink', function (done) {
    createProperty(done, 'itemlink', null, true, [3, 5]);
  });

  it('attach property: itemlink', function (done) {
    attachPropertyToType(done, 'itemlink');
  });

  it('create property: itemlinks', function (done) {
    createProperty(done, 'itemlinks', [], true, [3, 5]);
  });

  it('attach property: itemlinks', function (done) {
    attachPropertyToType(done, 'itemlinks');
  });

  it('create property: list', function (done) {
    createProperty(done, 'list', null, true, null, ['myitemlist1', 'myitemlist2', 'itemlist11']);
  });

  it('attach property: list', function (done) {
    attachPropertyToType(done, 'list');
  });

  it('create property: number', function (done) {
    createProperty(done, 'number', 756);
  });

  it('attach property: number', function (done) {
    attachPropertyToType(done, 'number');
  });

  it('create property: propertylink', function (done) {
    createProperty(done, 'propertylink', 2);
  });

  it('attach property: propertylink', function (done) {
    attachPropertyToType(done, 'propertylink');
  });

  it('create property: string', function (done) {
    createProperty(done, 'string', 'super string');
  });

  it('attach property: string', function (done) {
    attachPropertyToType(done, 'string');
  });

  it('create property: text', function (done) {
    createProperty(done, 'text', 'it can be a very long text here');
  });

  it('attach property: text', function (done) {
    attachPropertyToType(done, 'text');
  });

  it('create property: time', function (done) {
    createProperty(done, 'time', '08:36:08');
  });

  it('attach property: time', function (done) {
    attachPropertyToType(done, 'time');
  });

  it('create property: typelink', function (done) {
    createProperty(done, 'typelink', 2);
  });

  it('attach property: typelink', function (done) {
    attachPropertyToType(done, 'typelink');
  });

  it('create property: typelinks', function (done) {
    createProperty(done, 'typelinks', []);
  });

  it('attach property: typelinks', function (done) {
    attachPropertyToType(done, 'typelinks');
  });

  // passwordhash

  it('get list values', function (done) {
    request
      .get('/v1/config/properties/' + global.properties.list)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.array(response.body.listvalues));
        for (const listvalue of response.body.listvalues) {
          global.listvalues[listvalue.value] = listvalue.id;
          global.listvaluesById[listvalue.id] = listvalue.value;
        }
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create laptop `LPT123`', function (done) {
    request
      .post('/v1/items')
      .send({
        name: 'LPT123',
        type_id: 3,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.laptop123 = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create laptop `LPT300`', function (done) {
    request
      .post('/v1/items')
      .send({
        name: 'LPT300',
        type_id: 3,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.laptop300 = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create laptop `LPT012`', function (done) {
    request
      .post('/v1/items')
      .send({
        name: 'LPT012',
        type_id: 3,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.laptop012 = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create antivirus `Avast`', function (done) {
    request
      .post('/v1/items')
      .send({
        name: 'Avast',
        type_id: 5,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.antivirusAvast = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create antivirus `AVG`', function (done) {
    request
      .post('/v1/items')
      .send({
        name: 'AVG',
        type_id: 5,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.antivirusAVG = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
