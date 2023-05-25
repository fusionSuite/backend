const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

global.properties = {
  boolean: 0,
  date: 0,
  datetime: 0,
  decimal: 0,
  integer: 0,
  itemlink: 0,
  itemlinks: 0,
  list: 0,
  number: 0,
  propertylink: 0,
  string: 0,
  text: 0,
  time: 0,
  typelink: 0,
  typelinks: 0,
};

global.changesEntries = 0;

describe('changes | items | prepare type and properties', function () {
  it('initial number of changes rows in database table', function (done) {
    requestDB
      .get('/count/changes')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt = response.body.count;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  it('create a new type', function (done) {
    request
      .post('/v1/config/types')
      .send({
        name: 'type for items and properties changes',
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

  it('check changes rows in database table - must not have more changes', function (done) {
    requestDB
      .get('/count/changes/' + global.changesCnt)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.equal(global.changesCnt, response.body.count));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  // create a property on all different valuetype
  it('create a new boolean property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type boolean',
        internalname: 'testforboolean',
        valuetype: 'boolean',
        listvalues: [],
        default: true,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyBooleanId = response.body.id;
        global.properties.boolean = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new date property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type date',
        internalname: 'testfordate',
        valuetype: 'date',
        listvalues: [],
        default: '2022-11-11',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyDateId = response.body.id;
        global.properties.date = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new datetime property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type datetime',
        internalname: 'testfordatetime',
        valuetype: 'datetime',
        listvalues: [],
        default: '2022-11-11 21:58:16',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyDatetimeId = response.body.id;
        global.properties.datetime = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new decimal property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type decimal',
        internalname: 'testfordecimal',
        valuetype: 'decimal',
        listvalues: [],
        default: 40.56,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyDecimalId = response.body.id;
        global.properties.decimal = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new integer property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type integer',
        internalname: 'testforinteger',
        valuetype: 'integer',
        listvalues: [],
        default: -35005,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyIntegerId = response.body.id;
        global.properties.integer = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new itemlink property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type itemlink',
        internalname: 'testforitemlink',
        valuetype: 'itemlink',
        listvalues: ['users'],
        default: 2,
        allowedtypes: [2],
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyItemlinklId = response.body.id;
        global.properties.itemlink = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new itemlinks property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type itemlinks',
        internalname: 'testforitemlinks',
        valuetype: 'itemlinks',
        listvalues: ['users'],
        default: [2],
        allowedtypes: [2],
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyItemlinksId = response.body.id;
        global.properties.itemlinks = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new list property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type list',
        internalname: 'testforlist',
        valuetype: 'list',
        listvalues: ['test 1', 'test 2', 'test 3'],
        default: 'test 1',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyListId = response.body.id;
        global.properties.list = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new number property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type number',
        internalname: 'testfornumber',
        valuetype: 'number',
        listvalues: [],
        default: 42,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyNumberId = response.body.id;
        global.properties.number = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new propertylink property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type propertylink',
        internalname: 'testforpropertylink',
        valuetype: 'propertylink',
        listvalues: [],
        default: 1,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyPropertylinkId = response.body.id;
        global.properties.propertylink = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new string property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type string',
        internalname: 'testforstring',
        valuetype: 'string',
        listvalues: [],
        default: 'my default value',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyStringId = response.body.id;
        global.properties.string = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new text property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type text',
        internalname: 'testfortext',
        valuetype: 'text',
        listvalues: [],
        default: 'my default value\nMultiple lines',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyTextId = response.body.id;
        global.properties.text = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new time property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type time',
        internalname: 'testfortime',
        valuetype: 'time',
        listvalues: [],
        default: '07:14:58',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyTimeId = response.body.id;
        global.properties.time = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new typelink property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type typelink',
        internalname: 'testfortypelink',
        valuetype: 'typelink',
        listvalues: [],
        default: 1,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyTypelinklId = response.body.id;
        global.properties.typelink = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new typelinks property', function (done) {
    request
      .post('/v1/config/properties')
      .send({
        name: 'property with type typelinks',
        internalname: 'testfortypelinks',
        valuetype: 'typelinks',
        listvalues: [],
        default: [1],
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.propertyTypelinksId = response.body.id;
        global.properties.typelinks = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('re-initial number of changes rows in database table', function (done) {
    requestDB
      .get('/count/changes')
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.changesCnt = response.body.count;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.body);
        }
        return done();
      });
  });

  // attach properties to the type
  // eslint-disable-next-line mocha/no-setup-in-describe
  for (const propertyType in global.properties) {
    it('Attach a property to the type ' + propertyType, function (done) {
      request
        .post('/v1/config/types/' + global.typeId.toString() + '/property/' + global.properties[propertyType].toString())
        .send()
        .set('Accept', 'application/json')
        .set('Authorization', 'Bearer ' + global.token)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {})
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.text);
          }
          return done();
        });
    });

    it('check changes rows in database table - must have only 1 rows more', function (done) {
      requestDB
        .get('/count/changes/' + global.changesCnt)
        .expect(200)
        .expect('Content-Type', /json/)
        .expect(function (response) {
          global.changesCnt += 1;
          assert(is.equal(global.changesCnt, response.body.count), 'have ' + response.body.count + ' instead ' + global.changesCnt);
          assert(is.equal('admin added the property "property with type ' + propertyType + '"', response.body.rows[0].message), 'wrong message');
          assert(is.null(response.body.rows[0].old_value), 'old value is wrong ' + response.body.rows[0].old_value);
          assert(is.equal('{"id":' + global.properties[propertyType] + ',"name":"property with type ' + propertyType + '"}', response.body.rows[0].new_value), 'new value is wrong, must be: ' + response.body.rows[0].new_value);
        })
        .end(function (err, response) {
          if (err) {
            return done(err + ' | Response: ' + response.body);
          }
          return done();
        });
    });
  }

  it('create a user', function (done) {
    request
      .post('/v1/items')
      .send({
        name: 'user1',
        type_id: 2,
        organization_id: 1,
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 2));
        assert(is.integer(response.body.id));
        assert(is.integer(response.body.id_bytype));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.user1 = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
