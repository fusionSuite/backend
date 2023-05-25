const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const defineValuetype = (done, type) => {
  global.itemPropertyName = type;
  return done();
};

const createType = (done, itemPropertyName) => {
  request
    .post('/v1/config/types')
    .send({ name: 'Test ' + itemPropertyName })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.propertyCount(response.body, 1));

      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.propertytypesid = response.body.id;
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const createProperty = (done, value, canbenull = true, allowedtypes = null) => {
  const data = {
    name: 'Test for ' + global.itemPropertyName,
    internalname: 'testfor' + global.itemPropertyName,
    valuetype: global.itemPropertyName,
    regexformat: '',
    listvalues: [],
    default: value,
    unit: '',
    description: 'Test of the type ' + global.itemPropertyName,
  };
  if (!canbenull) {
    data.canbenull = false;
  }
  if (allowedtypes !== null) {
    data.allowedtypes = allowedtypes;
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
      global.propertyvaluesid = response.body.id;
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const checkProperty = (done, value, allowedtypes = []) => {
  request
    .get('/v1/config/properties/' + global.propertyvaluesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.equal(response.body.name, 'Test for ' + global.itemPropertyName), 'Property name is not good');
      if (value === null) {
        assert(is.null(response.body.default), 'Property default is not null');
      } else if (Array.isArray(value)) {
        assert(is.not.null(response.body.default), 'Property default must not be null');
        assert(is.equal(response.body.default.length, value.length), 'Property default not have the same length');
      } else {
        assert(is.equal(response.body.default, value), 'Property value is not good');
      }
      assert(is.array(response.body.allowedtypes), 'The allowedtypes field must be an array');
      assert(is.equal(allowedtypes.length, response.body.allowedtypes.length), 'Property allowedtypes must have same number of elements');
      if (allowedtypes.length > 0) {
        const allowedtypesIds = [];
        response.body.allowedtypes.forEach(type => {
          assert.deepEqual(Object.keys(type), ['id', 'name', 'internalname'], 'Allowedtypes must have same right fields');
          allowedtypesIds.push(type.id);
        });
        assert.deepEqual(allowedtypesIds, allowedtypes, 'The type of allowedtypes isn\'t in expected allowedlist, ' + allowedtypesIds + ' / ' + allowedtypes);
      }
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const attachPropertyToType = (done) => {
  request
    .post('/v1/config/types/' + global.propertytypesid.toString() + '/property/' + global.propertyvaluesid.toString())
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

const deleteItem = (done) => {
  request
    .delete('/v1/items/' + global.itemId.toString())
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
};

const deleteType = (done) => {
  request
    .delete('/v1/config/types/' + global.propertytypesid.toString())
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
};

const deleteProperty = (done) => {
  request
    .delete('/v1/config/properties/' + global.propertyvaluesid)
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
};

const createItemWithError = (done, value, errorMessage) => {
  const type = global.itemPropertyName;
  request
    .post('/v1/items')
    .send({
      name: 'test date',
      type_id: global.propertytypesid,
      properties: [
        {
          property_id: global.propertyvaluesid,
          value,
        },
      ],
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, errorMessage + ' (property Test for ' + type + ' - ' + global.propertyvaluesid + ')'));
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const updateItemWithError = (done, value, errorMessage) => {
  const type = global.itemPropertyName;
  request
    .patch('/v1/items/' + global.itemId + '/property/' + global.propertyvaluesid)
    .send({
      value,
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, errorMessage + ' (property Test for ' + type + ' - ' + global.propertyvaluesid + ')'));
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const addLinkDedicatedEndpoint = (done, value) => {
  request
    .post('/v1/items/' + global.itemId + '/property/' + global.propertyvaluesid + '/' + global.itemPropertyName)
    .send({
      value,
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
};

const deleteLinkDedicatedEndpoint = (done, value) => {
  request
    .delete('/v1/items/' + global.itemId + '/property/' + global.propertyvaluesid + '/' + global.itemPropertyName + '/' + value)
    .send()
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
};

const addLinkDedicatedEndpointWithError = (done, value, errorMessage) => {
  request
    .post('/v1/items/' + global.itemId + '/property/' + global.propertyvaluesid + '/' + global.itemPropertyName)
    .send({
      value,
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, errorMessage));
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const deleteLinkDedicatedEndpointWithError = (done, value, errorCode, errorMessage) => {
  request
    .delete('/v1/items/' + global.itemId + '/property/' + global.propertyvaluesid + '/' + global.itemPropertyName + '/' + value)
    .send()
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(errorCode)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, errorMessage));
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const getListIds = (done) => {
  request
    .get('/v1/config/properties/' + global.propertyvaluesid.toString())
    .send()
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      this.global.listIds = {};
      for (const prop of response.body.listvalues) {
        this.global.listIds[prop.value] = prop.id;
      }
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const updateProperty = (done, value, httpCode, errorMessage = null, allowedtypes = null) => {
  const payload = {
    default: value,
  };
  if (allowedtypes !== null) {
    payload.allowedtypes = allowedtypes;
  }
  request
    .patch('/v1/config/properties/' + global.propertyvaluesid.toString())
    .send(payload)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(httpCode)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      if (httpCode !== 200) {
        assert(is.propertyCount(response.body, 2));
        assert(validator.equals(response.body.status, 'error'));
        if (errorMessage !== null) {
          assert(validator.equals(response.body.message, 'The Default is not valid type'));
        }
      }
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

exports.defineValuetype = defineValuetype;
exports.createType = createType;
exports.createProperty = createProperty;
exports.checkProperty = checkProperty;
exports.attachPropertyToType = attachPropertyToType;
exports.deleteItem = deleteItem;
exports.deleteType = deleteType;
exports.deleteProperty = deleteProperty;
exports.createItemWithError = createItemWithError;
exports.updateItemWithError = updateItemWithError;
exports.addLinkDedicatedEndpoint = addLinkDedicatedEndpoint;
exports.deleteLinkDedicatedEndpoint = deleteLinkDedicatedEndpoint;
exports.addLinkDedicatedEndpointWithError = addLinkDedicatedEndpointWithError;
exports.deleteLinkDedicatedEndpointWithError = deleteLinkDedicatedEndpointWithError;
exports.getListIds = getListIds;
exports.updateProperty = updateProperty;
