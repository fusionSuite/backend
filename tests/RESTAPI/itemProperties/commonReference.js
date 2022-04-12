const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { exit } = require('process');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const setReference = () => {
  it('define the reference', function(done) {
    global.referenceId = global.itemId;
    global.referencePropertytypesid = global.propertytypesid;
    global.referencePropertyvaluesid = global.propertyvaluesid;
    return done();
  });
}

const createProperty = (canbenull = true) => {
  it('create a new property - type ' + global.itemPropertyName, function(done) {
    value = global.referenceId;
    data = {
      name: 'Test for ' + global.itemPropertyName,
      internalname: 'testfor' + global.itemPropertyName,
      valuetype: global.itemPropertyName,
      regexformat: '',
      listvalues: [],
      unit: '',
      default: value,
      description: 'Test of the type ' + global.itemPropertyName
    };
    if (!canbenull) {
      data['canbenull'] = false;
    }
    request
    .post('/v1/config/properties')
    .send(data)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.propertyvaluesid = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}

const deleteItem = () => {
  it('Soft delete the item' + global.referenceId, function(done) {
    request
    .delete('/v1/items/' + global.referenceId.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect('Content-Type', /json/)
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
  it('Hard delete the item ' + global.referenceId, function(done) {
    request
    .delete('/v1/items/' + global.referenceId.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect('Content-Type', /json/)
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}


const deleteType = () => {
  it('Soft delete the type: test ' + global.itemPropertyName, function(done) {
    request
    .delete('/v1/config/types/' + global.referencePropertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect('Content-Type', /json/)
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
  it('Hard delete the type: test ' + global.itemPropertyName, function(done) {
    request
    .delete('/v1/config/types/' + global.referencePropertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect('Content-Type', /json/)
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}

const deleteProperty = () => {
  it('Delete the property', function(done) {
    request
    .delete('/v1/config/properties/' + global.referencePropertyvaluesid)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect('Content-Type', /json/)
    .expect(200)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
};

const createItemAndCheckOk = (withProperty) => {
  it('create a new item', function(done) {
    value = global.referenceId;
    properties = [];
    if (withProperty) {
      properties = [
        {
          property_id: global.propertyvaluesid,
          value: value
        }
      ];
    }
    request
    .post('/v1/items')
    .send({
      name: 'test date',
      type_id: global.propertytypesid,
      properties: properties
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.itemId = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('Get the item to check value is good', function(done) {
    value = global.referenceId;
    request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (let prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.object(prop.value), 'the property value must be an object type');
          assert(is.equal(prop.value.id, value), 'the property id value must be '+value);
        }
      }
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}

const updateItemAndCheckOk = () => {
  it('Update the item with referenceid value', function(done) {
    value = global.referenceId;
    request
    .patch('/v1/items/'+global.itemId+'/property/'+global.propertyvaluesid)
    .send({
      value: value
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

  it('Get the item modified of the type and verify the value ('+global.itemPropertyName+') is right', function(done) {
    request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      assert(is.equal(response.body.length, 1));
      firstElement = response.body[0];
      assert(is.propertyCount(firstElement.properties, 1));
      for (let prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be '.value);
        } else {
          assert(is.object(prop.value), 'the property value must be an object type');
          assert(is.equal(prop.value.id, value), 'the property id value must be '+value);
        }
      }
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}

const updateItemToDefault = () => {
  it('Update the item to default value', function(done) {
    value = global.referenceId;
    request
    .patch('/v1/items/'+global.itemId+'/property/'+global.propertyvaluesid)
    .send({
      value: null,
      reset_to_default: true
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

  it('Get the item modified of the type and verify the value ('+global.itemPropertyName+') is right', function(done) {
    request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      firstElement = response.body[0];
      assert(is.propertyCount(firstElement.properties, 1));
      for (let prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be '.value);
        } else {
          assert(is.object(prop.value), 'the property value must be an object type');
          assert(is.equal(prop.value.id, value), 'the property id value must be '+value);
        }
      }
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}


exports.setReference = setReference;
exports.createProperty = createProperty;
exports.deleteItem = deleteItem;
exports.deleteType = deleteType;
exports.deleteProperty = deleteProperty;
exports.createItemAndCheckOk = createItemAndCheckOk;
exports.updateItemAndCheckOk = updateItemAndCheckOk;
exports.updateItemToDefault = updateItemToDefault;
