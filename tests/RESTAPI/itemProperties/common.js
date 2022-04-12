const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

const defineValuetype = (type) => {
  it('define the type ' + type, function(done) {
    global.itemPropertyName = type;
    return done();
  });
}

const createType = () => {
  it('create a new type ' + global.itemPropertyName, function(done) {
    request
    .post('/v1/config/types')
    .send({name: 'Test ' + global.itemPropertyName})
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));

      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.propertytypesid = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}

const createProperty = (value, canbenull = true) => {
  it('create a new property - type ' + global.itemPropertyName, function(done) {
    data = {
      name: 'Test for ' + global.itemPropertyName,
      internalname: 'testfor' + global.itemPropertyName,
      valuetype: global.itemPropertyName,
      regexformat: '',
      listvalues: [],
      default: value,
      unit: '',
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

  it('Get the property to check value is good', function(done) {
    request
    .get('/v1/config/properties/' + global.propertyvaluesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.equal(response.body.name, 'Test for ' + global.itemPropertyName), 'Property name is not good');
      if (value === null) {
        assert(is.null(response.body.default), 'Property default is not null');
      } else if (Array.isArray(value)) {
        assert(is.equal(response.body.default.length, value.length), 'Property default not have the same length');
      } else {
        assert(is.equal(response.body.default, value), 'Property value is not good');
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


const attachPropertyToType = () => {
  it('Attach a property to the type ' + global.itemPropertyName, function(done) {
    request
    .post('/v1/config/types/' + global.propertytypesid.toString() + '/property/' + global.propertyvaluesid.toString())
    .send()
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {

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
  it('Soft delete the item' + global.itemId, function(done) {
    request
    .delete('/v1/items/' + global.itemId.toString())
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
  it('Hard delete the item ' + global.itemId, function(done) {
    request
    .delete('/v1/items/' + global.itemId.toString())
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
    .delete('/v1/config/types/' + global.propertytypesid.toString())
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
    .delete('/v1/config/types/' + global.propertytypesid.toString())
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
    .delete('/v1/config/properties/' + global.propertyvaluesid)
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

const createItemWithError = (description, value, errorMessage) => {
  it('try create a new item but return error (' + description + ')', function(done) {
    type = global.itemPropertyName;
    request
    .post('/v1/items')
    .send({
      name: 'test date',
      type_id: global.propertytypesid,
      properties:[
        {
          property_id: global.propertyvaluesid,
          value: value
        }
      ]
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, errorMessage + ' (property Test for ' + type + ' - ' + global.propertyvaluesid + ')'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}

const createItemAndCheckOk = (withProperty, value) => {
  it('create a new item', function(done) {
    // Special case for list
    if (global.itemPropertyName === 'list' && withProperty && global.listIds[value] !== undefined) {
      value = global.listIds[value];
    }
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
        } else if (global.itemPropertyName === 'date' && value === '') {
          today = new Date();
          month = String(today.getMonth()+1).padStart(2, "0");
          day = String(today.getDate()).padStart(2, "0");
          assert(is.string(prop.value), 'the property value must be a date type');
          assert(is.equal(prop.value, today.getFullYear()+'-'+month+'-'+day, 'the property value must be the date of today: '+today.getFullYear()+'-'+month+'-'+day));
        } else if (global.itemPropertyName === 'datetime' && value === '') {
          today = new Date();
          month = String(today.getMonth()+1).padStart(2, "0");
          day = String(today.getDate()).padStart(2, "0");
          hour = String(today.getHours()).padStart(2, "0");
          minute = String(today.getMinutes()).padStart(2, "0");
          second = String(today.getSeconds()).padStart(2, "0");
          assert(is.string(prop.value), 'the property value must be a datetime type');
          dateSpl = prop.value.split(' ');
          assert(is.array(dateSpl), 'the date has be right splited to check it');
          assert(is.equal(dateSpl.length, 2), 'the date has be right splited in 2 parts');
          assert(is.timeString(dateSpl[1]), 'the property value (second part) must be a time string');
          assert(is.startWith(prop.value, today.getFullYear()+'-'+month+'-'+day+' '+hour+':'+minute+':', 'the property value must be the datetime of today: '+today.getFullYear()+'-'+month+'-'+day+' '+hour+':'+minute+':'+second));
        } else if (global.itemPropertyName === 'time' && value === '') {
          today = new Date();
          hour = String(today.getHours()).padStart(2, "0");
          minute = String(today.getMinutes()).padStart(2, "0");
          second = String(today.getSeconds()).padStart(2, "0");
          assert(is.string(prop.value), 'the property value must be a time type');
          assert(is.timeString(prop.value), 'the property value must be a time string');
          assert(is.startWith(prop.value, hour+':'+minute+':', 'the property value must be the time of today: '+hour+':'+minute+':'+second));
        } else if (global.itemPropertyName === 'boolean') {
          assert(is.boolean(prop.value), 'the property value must be a boolean type');
          assert(is.equal(prop.value, value), 'the property value must be '+value);
        } else if (global.itemPropertyName === 'decimal') {
          assert(is.decimal(prop.value), 'the property value must be a decimal type');
          assert(is.equal(prop.value, value), 'the property value must be '+value);
        } else if (global.itemPropertyName === 'integer') {
          assert(is.integer(prop.value), 'the property value must be a integer type');
          assert(is.equal(prop.value, value), 'the property value must be '+value);
        } else if (global.itemPropertyName === 'number') {
          assert(is.number(prop.value), 'the property value must be a number type');
          assert(is.equal(prop.value, value), 'the property value must be '+value);
        } else if (global.itemPropertyName === 'list') {
          assert(is.object(prop.value), 'the property value must be a number type');
          if (global.itemPropertyName === 'list' && global.listIds[value] !== undefined) {
            value = global.listIds[value];
          }
          assert(is.equal(prop.value.id, value), 'the property value must be '+value);
        } else if (['propertylink', 'typelink'].includes(global.itemPropertyName)) {
          assert(is.object(prop.value), 'the property value must be an object type');
          assert(is.equal(prop.value.id, value), 'the property id value must be '+value);
        } else if (global.itemPropertyName === 'typelinks') {
          assert(is.array(prop.value), 'the property value must be an array of types');
          assert(is.equal(prop.value.length, value.length), 'the property value must have same number of element than value');
          for (let [idx, item] of prop.value.entries()) {
            assert(is.object(item), 'the property value item must be an typelinks type');
            assert(is.propertyDefined(item, 'name'), 'the property value item must be an object and must have name property');
            assert(is.propertyDefined(item, 'id'), 'the property value item must be an object and must have id property');
            assert(is.equal(item.id, value[idx]), 'the property value item must be an object and the id is '+value[idx]);
          }
        } else {
          assert(is.string(prop.value), 'the property value must be a string type');
          assert(is.equal(prop.value, value), 'the property value must be '+value);
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

const updateItemAndCheckOk = (value) => {
  it('Update the item with '+value+' value', function(done) {
    // Special case for list
    if (global.itemPropertyName === 'list' && global.listIds[value] !== undefined) {
      value = global.listIds[value];
    }
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
      strValueType = ['date', 'datetime', 'time'];
      for (let prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be '.value);
        } else if (global.itemPropertyName === 'date' && value === '') {
          today = new Date();
          month = String(today.getMonth()+1).padStart(2, "0");
          day = String(today.getDate()).padStart(2, "0");
          assert(is.string(prop.value), 'the property value must be a date type');
          assert(is.equal(prop.value, today.getFullYear()+'-'+month+'-'+day, 'the property value must be the date of today: '+today.getFullYear()+'-'+month+'-'+day));
        } else if (global.itemPropertyName === 'datetime' && value === '') {
          today = new Date();
          month = String(today.getMonth()+1).padStart(2, "0");
          day = String(today.getDate()).padStart(2, "0");
          hour = String(today.getHours()).padStart(2, "0");
          minute = String(today.getMinutes()).padStart(2, "0");
          second = String(today.getSeconds()).padStart(2, "0");
          assert(is.string(prop.value), 'the property value must be a datetime type');
          dateSpl = prop.value.split(' ');
          assert(is.array(dateSpl), 'the date has be right splited to check it');
          assert(is.equal(dateSpl.length, 2), 'the date has be right splited in 2 parts');
          assert(is.timeString(dateSpl[1]), 'the property value (second part) must be a time string');
          assert(is.startWith(prop.value, today.getFullYear()+'-'+month+'-'+day+' '+hour+':'+minute+':', 'the property value must be the datetime of today: '+today.getFullYear()+'-'+month+'-'+day+' '+hour+':'+minute+':'+second));
        } else if (global.itemPropertyName === 'time' && value === '') {
          today = new Date();
          hour = String(today.getHours()).padStart(2, "0");
          minute = String(today.getMinutes()).padStart(2, "0");
          second = String(today.getSeconds()).padStart(2, "0");
          assert(is.string(prop.value), 'the property value must be a time type');
          assert(is.timeString(prop.value), 'the property value must be a time string');
          assert(is.startWith(prop.value, hour+':'+minute+':', 'the property value must be the time of today: '+hour+':'+minute+':'+second));
        } else if (['propertylink', 'typelink'].includes(global.itemPropertyName)) {
          assert(is.object(prop.value), 'the property value must be a '+global.itemPropertyName+' type');
          assert(is.equal(prop.value.id, value), 'the property id value must be '.value);
        } else if (global.itemPropertyName === 'typelinks') {
          assert(is.array(prop.value), 'the property value must be an array of types');
          assert(is.equal(prop.value.length, value.length), 'the property value must have same number of element than value');
          for (let [idx, item] of prop.value.entries()) {
            assert(is.object(item), 'the property value item must be an typelinks type');
            assert(is.propertyDefined(item, 'name'), 'the property value item must be an object and must have name property');
            assert(is.propertyDefined(item, 'id'), 'the property value item must be an object and must have id property');
            assert(is.equal(item.id, value[idx]), 'the property value item must be an object and the id is 1');
          }
        } else if (global.itemPropertyName === 'list') {
          assert(is.object(prop.value), 'the property value must be a number type');
          assert(is.equal(prop.value.id, value), 'the property value must be '+value);
        } else {
          if (global.itemPropertyName === 'boolean') {
            assert(is.boolean(prop.value), 'the property value must be a boolean type');
          } else if (global.itemPropertyName === 'decimal') {
            assert(is.decimal(prop.value), 'the property value must be a decimal type');
          } else if (global.itemPropertyName === 'integer') {
            assert(is.integer(prop.value), 'the property value must be a integer type');
          } else if (global.itemPropertyName === 'number') {
            assert(is.number(prop.value), 'the property value must be a number type');
          } else if (strValueType.includes(global.itemPropertyName)) {
            assert(is.string(prop.value), 'the property value must be a '+global.itemPropertyName+' type');
          }
          assert(is.equal(prop.value, value), 'the property value must be '.value);
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

const updateItemToDefault = (value) => {
  it('Update the item to default value', function(done) {
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
      strValueType = ['date', 'datetime', 'time'];
      for (let prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be '.value);
        } else if (global.itemPropertyName === 'date' && value === '') {
          today = new Date();
          month = String(today.getMonth()+1).padStart(2, "0");
          day = String(today.getDate()).padStart(2, "0");
          assert(is.string(prop.value), 'the property value must be a date type');
          assert(is.equal(prop.value, today.getFullYear()+'-'+month+'-'+day, 'the property value must be the date of today: '+today.getFullYear()+'-'+month+'-'+day));
        } else if (global.itemPropertyName === 'datetime' && value === '') {
          today = new Date();
          month = String(today.getMonth()+1).padStart(2, "0");
          day = String(today.getDate()).padStart(2, "0");
          hour = String(today.getHours()).padStart(2, "0");
          minute = String(today.getMinutes()).padStart(2, "0");
          second = String(today.getSeconds()).padStart(2, "0");
          assert(is.string(prop.value), 'the property value must be a datetime type');
          dateSpl = prop.value.split(' ');
          assert(is.array(dateSpl), 'the date has be right splited to check it');
          assert(is.equal(dateSpl.length, 2), 'the date has be right splited in 2 parts');
          assert(is.timeString(dateSpl[1]), 'the property value (second part) must be a time string');
          assert(is.startWith(prop.value, today.getFullYear()+'-'+month+'-'+day+' '+hour+':'+minute+':', 'the property value must be the datetime of today: '+today.getFullYear()+'-'+month+'-'+day+' '+hour+':'+minute+':'+second));
        } else if (global.itemPropertyName === 'time' && value === '') {
          today = new Date();
          hour = String(today.getHours()).padStart(2, "0");
          minute = String(today.getMinutes()).padStart(2, "0");
          second = String(today.getSeconds()).padStart(2, "0");
          assert(is.string(prop.value), 'the property value must be a time type');
          assert(is.timeString(prop.value), 'the property value must be a time string');
          assert(is.startWith(prop.value, hour+':'+minute+':', 'the property value must be the time of today: '+hour+':'+minute+':'+second));
        } else if (['propertylink', 'typelink'].includes(global.itemPropertyName)) {
          assert(is.object(prop.value), 'the property value must be an object type');
          assert(is.equal(prop.value.id, value), 'the property id value must be '.value);
        } else if (global.itemPropertyName === 'typelinks') {
          assert(is.array(prop.value), 'the property value must be an array of types');
          assert(is.equal(prop.value.length, value.length), 'the property value must have same number of element than value');
          for (let [idx, item] of prop.value.entries()) {
            assert(is.object(item), 'the property value item must be an typelinks type');
            assert(is.propertyDefined(item, 'name'), 'the property value item must be an object and must have name property');
            assert(is.propertyDefined(item, 'id'), 'the property value item must be an object and must have id property');
            assert(is.equal(item.id, value[idx]), 'the property value item must be an object and the id is 1');
          }
        } else if (global.itemPropertyName === 'list') {
          assert(is.object(prop.value), 'the property value must be a number type');
          value = global.listIds[value];
          assert(is.equal(prop.value.id, value), 'the property value must be '+value);
        } else {
          if (global.itemPropertyName === 'boolean') {
            assert(is.boolean(prop.value), 'the property value must be a boolean type');
          } else if (global.itemPropertyName === 'decimal') {
            assert(is.decimal(prop.value), 'the property value must be a decimal type');
          } else if (global.itemPropertyName === 'integer') {
            assert(is.integer(prop.value), 'the property value must be a integer type');
          } else if (global.itemPropertyName === 'number') {
            assert(is.number(prop.value), 'the property value must be a number type');
          } else if (strValueType.includes(global.itemPropertyName)) {
            assert(is.string(prop.value), 'the property value must be a '+global.itemPropertyName+' type');
          }
          assert(is.equal(prop.value, value), 'the property value must be '.value);
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

const updateItemWithError = (description, value, errorMessage) => {
  it('try update the item but return error (' + description + ')', function(done) {
    request
    .patch('/v1/items/'+global.itemId+'/property/'+global.propertyvaluesid)
    .send({
      value: value
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, errorMessage + ' (property Test for ' + type + ' - ' + global.propertyvaluesid + ')'));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}

const addLinkDedicatedEndpointAndCheckOk = (value, result) => {
  it('Update the item with '+value+' value', function(done) {
    request
    .post('/v1/items/'+global.itemId+'/property/'+global.propertyvaluesid+'/'+global.itemPropertyName)
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
      strValueType = ['date', 'datetime', 'time'];
      for (let prop of firstElement.properties) {
        if (global.itemPropertyName === 'typelinks') {
          assert(is.array(prop.value), 'the property value must be an array of types');
          assert(is.equal(prop.value.length, result.length), 'the property value must have same number of element than value');
          for (let [idx, item] of prop.value.entries()) {
            assert(is.object(item), 'the property value item must be an typelinks type');
            assert(is.propertyDefined(item, 'name'), 'the property value item must be an object and must have name property');
            assert(is.propertyDefined(item, 'id'), 'the property value item must be an object and must have id property');
            assert(is.equal(item.id, result[idx]), 'the property value item must be an object and the id is 1');
          }
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

const deleteLinkDedicatedEndpointAndCheckOk = (value, result) => {
  it('Update the item with '+value+' value', function(done) {
    request
    .delete('/v1/items/'+global.itemId+'/property/'+global.propertyvaluesid+'/'+global.itemPropertyName+'/'+value)
    .send()
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
      strValueType = ['date', 'datetime', 'time'];
      for (let prop of firstElement.properties) {
        if (global.itemPropertyName === 'typelinks') {
          assert(is.array(prop.value), 'the property value must be an array of types');
          assert(is.equal(prop.value.length, result.length), 'the property value must have same number of element than value');
          for (let [idx, item] of prop.value.entries()) {
            assert(is.object(item), 'the property value item must be an typelinks type');
            assert(is.propertyDefined(item, 'name'), 'the property value item must be an object and must have name property');
            assert(is.propertyDefined(item, 'id'), 'the property value item must be an object and must have id property');
            assert(is.equal(item.id, result[idx]), 'the property value item must be an object and the id is 1');
          }
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

const addLinkDedicatedEndpointWithError = (description, value, errorMessage) => {
  it('try add a new '+global.itemPropertyName+' but return error (' + description + ')', function(done) {
    type = global.itemPropertyName;
    request
    .post('/v1/items/'+global.itemId+'/property/'+global.propertyvaluesid+'/'+global.itemPropertyName)
    .send({
      value: value
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(400)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, errorMessage));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}

const deleteLinkDedicatedEndpointWithError = (description, value, errorCode, errorMessage) => {
  it('try add a new '+global.itemPropertyName+' but return error (' + description + ')', function(done) {
    type = global.itemPropertyName;
    request
    .delete('/v1/items/'+global.itemId+'/property/'+global.propertyvaluesid+'/'+global.itemPropertyName+'/'+value)
    .send()
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(errorCode)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 2));
      assert(validator.equals(response.body.status, 'error'));
      assert(validator.equals(response.body.message, errorMessage));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
}

const getListIds = () => {
  it('Get the list of ids', function(done) {
    request
    .get('/v1/config/properties/' + global.propertyvaluesid.toString())
    .send()
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      this.global.listIds = {};
      for (let prop of response.body.listvalues) {
        this.global.listIds[prop.value] = prop.id;
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

const updateProperty = (value, httpCode) => {
  it('create a new property - type ' + global.itemPropertyName, function(done) {
    request
    .patch('/v1/config/properties/' + global.propertyvaluesid.toString())
    .send({
      default: value,
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(httpCode)
    .expect('Content-Type', /json/)
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });
  if (httpCode === 200) {
    it('Get the property to check value is good', function(done) {
      request
      .get('/v1/config/properties/' + global.propertyvaluesid.toString())
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function(response) {
        if (value === null) {
          assert(is.null(response.body.default), 'Property default is not null');
        } else if (Array.isArray(value)) {
          assert(is.equal(response.body.default.length, value.length), 'Property default not have the same length');
        } else {
          assert(is.equal(response.body.default, value), 'Property value is not good');
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
}


exports.defineValuetype = defineValuetype;
exports.createType = createType;
exports.createProperty = createProperty;
exports.attachPropertyToType = attachPropertyToType;
exports.deleteItem = deleteItem;
exports.deleteType = deleteType;
exports.deleteProperty = deleteProperty;
exports.createItemWithError = createItemWithError;
exports.createItemAndCheckOk = createItemAndCheckOk;
exports.updateItemAndCheckOk = updateItemAndCheckOk;
exports.updateItemToDefault = updateItemToDefault;
exports.updateItemWithError = updateItemWithError;
exports.addLinkDedicatedEndpointAndCheckOk = addLinkDedicatedEndpointAndCheckOk;
exports.deleteLinkDedicatedEndpointAndCheckOk = deleteLinkDedicatedEndpointAndCheckOk;
exports.addLinkDedicatedEndpointWithError = addLinkDedicatedEndpointWithError;
exports.deleteLinkDedicatedEndpointWithError = deleteLinkDedicatedEndpointWithError;
exports.getListIds = getListIds;
exports.updateProperty = updateProperty;
