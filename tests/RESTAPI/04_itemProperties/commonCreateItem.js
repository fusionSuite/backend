const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
const requestDB = supertest('http://127.0.0.1:8012');

const createItem = (done, withProperty, value = null) => {
  // Special case for list
  if (global.itemPropertyName === 'list' && withProperty && global.listIds[value] !== undefined) {
    value = global.listIds[value];
  }
  let properties = [];
  if (withProperty) {
    properties = [
      {
        property_id: global.propertyvaluesid,
        value,
      },
    ];
  }
  request
    .post('/v1/items')
    .send({
      name: 'test date',
      type_id: global.propertytypesid,
      properties,
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
      global.itemId = response.body.id;
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const updateItem = (done, value) => {
  // Special case for list
  if (global.itemPropertyName === 'list' && global.listIds[value] !== undefined) {
    value = global.listIds[value];
  }
  request
    .patch('/v1/items/' + global.itemId + '/property/' + global.propertyvaluesid)
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

const updateItemToDefault = (done) => {
  request
    .patch('/v1/items/' + global.itemId + '/property/' + global.propertyvaluesid)
    .send({
      value: null,
      reset_to_default: true,
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

const checkItemOkBoolean = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.boolean(prop.value), 'the property value must be a boolean type');
          assert(is.equal(prop.value, value), 'the property value must be ' + value);
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

const checkItemOkDate = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else if (global.itemPropertyName === 'date' && value === '') {
          const today = new Date();
          const month = String(today.getMonth() + 1).padStart(2, '0');
          const day = String(today.getDate()).padStart(2, '0');
          assert(is.string(prop.value), 'the property value must be a date type');
          assert(is.equal(prop.value, today.getFullYear() + '-' + month + '-' + day, 'the property value must be the date of today: ' + today.getFullYear() + '-' + month + '-' + day));
        } else {
          assert(is.string(prop.value), 'the property value must be a string type');
          assert(is.equal(prop.value, value), 'the property value must be ' + value);
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

const checkItemOkDatetime = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else if (global.itemPropertyName === 'datetime' && value === '') {
          const now = new Date();
          const today = new Date(now.getTime() + (now.getTimezoneOffset() * 60 * 1000));
          const month = String(today.getMonth() + 1).padStart(2, '0');
          const day = String(today.getDate()).padStart(2, '0');
          const hour = String(today.getHours()).padStart(2, '0');
          const minute = String(today.getMinutes()).padStart(2, '0');
          const second = String(today.getSeconds()).padStart(2, '0');
          assert(is.string(prop.value), 'the property value must be a datetime type');
          const dateSpl = prop.value.split(' ');
          assert(is.array(dateSpl), 'the date has be right splited to check it');
          assert(is.equal(dateSpl.length, 2), 'the date has be right splited in 2 parts');
          assert(is.timeString(dateSpl[1]), 'the property value (second part) must be a time string');
          assert(is.startWith(prop.value, today.getFullYear() + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':', 'the property value must be the datetime of today: ' + today.getFullYear() + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second));
        } else {
          assert(is.string(prop.value), 'the property value must be a string type');
          assert(is.equal(prop.value, value), 'the property value must be ' + value);
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

const checkItemOkTime = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else if (global.itemPropertyName === 'time' && value === '') {
          const now = new Date();
          const today = new Date(now.getTime() + (now.getTimezoneOffset() * 60 * 1000));
          const hour = String(today.getHours()).padStart(2, '0');
          const minute = String(today.getMinutes()).padStart(2, '0');
          const second = String(today.getSeconds()).padStart(2, '0');
          assert(is.string(prop.value), 'the property value must be a time type');
          assert(is.timeString(prop.value), 'the property value must be a time string');
          assert(is.startWith(prop.value, hour + ':' + minute + ':', 'the property value must be the time of today: ' + hour + ':' + minute + ':' + second));
        } else {
          assert(is.string(prop.value), 'the property value must be a string type');
          assert(is.equal(prop.value, value), 'the property value must be ' + value);
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

const checkItemOkTypelink = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.object(prop.value), 'the property value must be an object type');
          assert(is.equal(prop.value.id, value), 'the property id value must be ' + value);
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

const checkItemOkTypelinks = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.array(prop.value), 'the property value must be an array of types');
          assert(is.equal(prop.value.length, value.length), 'the property value must have same number of element than value');
          for (const [idx, item] of prop.value.entries()) {
            assert(is.object(item), 'the property value item must be an typelinks type');
            assert(is.propertyDefined(item, 'name'), 'the property value item must be an object and must have name property');
            assert(is.propertyDefined(item, 'id'), 'the property value item must be an object and must have id property');
            assert(is.equal(item.id, value[idx]), 'the property value item must be an object and the id is ' + value[idx]);
          }
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

const checkItemOkDecimal = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.decimal(prop.value), 'the property value must be a decimal type');
          assert(is.equal(prop.value, value), 'the property value must be ' + value);
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

const checkItemOkInteger = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.integer(prop.value), 'the property value must be a integer type');
          assert(is.equal(prop.value, value), 'the property value must be ' + value);
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

const checkItemOkItemlink = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.object(prop.value), 'the property value must be an object type');
          assert(is.equal(prop.value.id, value), 'the property id value must be ' + value);
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

const checkItemOkItemlinks = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.array(prop.value), 'the property value must be an array of types');
          assert(is.equal(prop.value.length, value.length), 'the property value must have same number of element than value');
          for (const [idx, item] of prop.value.entries()) {
            assert(is.object(item), 'the property value item must be an itemlinks type');
            assert(is.propertyDefined(item, 'name'), 'the property value item must be an object and must have name property');
            assert(is.propertyDefined(item, 'id'), 'the property value item must be an object and must have id property');
            assert(is.equal(item.id, value[idx]), 'the property value item must be an object and the id is ' + value[idx]);
          }
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

const checkItemOkList = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.object(prop.value), 'the property value must be a number type');
          if (global.itemPropertyName === 'list' && global.listIds[value] !== undefined) {
            value = global.listIds[value];
          }
          assert(is.equal(prop.value.id, value), 'the property value must be ' + value);
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

const checkItemOkNumber = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.number(prop.value), 'the property value must be a number type');
          assert(is.equal(prop.value, value), 'the property value must be ' + value);
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

const checkItemOkString = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.string(prop.value), 'the property value must be a string type');
          assert(is.equal(prop.value, value), 'the property value must be ' + value);
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

const checkItemOkPropertylink = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.object(prop.value), 'the property value must be an object type');
          assert(is.equal(prop.value.id, value), 'the property id value must be ' + value);
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

const checkItemsOkPassword = (done, value) => {
  request
    .get('/v1/items/type/' + global.propertytypesid.toString())
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.not.empty(response.body), 'The body must contain something');
      assert(is.array(response.body), 'the body response must be an array');
      const firstElement = response.body[0];
      assert(is.equal(response.body.length, 1));
      assert(is.propertyCount(firstElement.properties, 1));
      for (const prop of firstElement.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.string(prop.value), 'the property value must be a string type');
          assert(is.equal(prop.value, value), 'the property value must be ' + value);
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

const checkItemOkPassword = (done, value) => {
  request
    .get('/v1/items/' + global.itemId)
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.propertyCount(response.body.properties, 1));
      for (const prop of response.body.properties) {
        if (value === null) {
          assert(is.null(prop.value), 'the property value must be null');
        } else {
          assert(is.string(prop.value), 'the property value must be a string type');
          assert(is.equal(prop.value, value), 'the property value must be ' + value);
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

const checkItemOkPasswordEncryptedDatabase = (done, value) => {
  requestDB
    .get('/item_property/itemid/' + global.itemId + '/propertyid/' + global.propertyvaluesid)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.equal(1, response.body.count), 'must have only 1 property');
      if (value === null) {
        assert(is.null(response.body.rows[0].value_password), 'the password must be null');
      } else {
        assert(is.not.null(response.body.rows[0].value_password), 'the password must not be null');
        assert(is.not.equal(value, response.body.rows[0].value_password), 'the password must be encrypted into database');
        assert(is.not.equal('', response.body.rows[0].value_password), 'the password must be encrypted into database and not empty');
      }
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const checkDefaultPropertyPasswordEncryptedDatabase = (done, value) => {
  requestDB
    .get('/property/' + global.propertyvaluesid)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.equal(1, response.body.count), 'must have only 1 property');
      if (value === null) {
        assert(is.null(response.body.rows[0].default_password), 'the password must be null');
      } else {
        assert(is.not.null(response.body.rows[0].default_password), 'the password must not be null');
        assert(is.not.equal(value, response.body.rows[0].default_password), 'the default password must be encrypted into database');
        assert(is.not.equal('', response.body.rows[0].value_password), 'the password must be encrypted into database and not empty');
      }
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const checkItemOkPasswordHashedDatabase = (done, value) => {
  requestDB
    .get('/item_property/itemid/' + global.itemId + '/propertyid/' + global.propertyvaluesid)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.equal(1, response.body.count), 'must have only 1 property');
      if (value === null) {
        assert(is.null(response.body.rows[0].value_passwordhash), 'the passwordhash must be null');
      } else {
        assert(is.not.null(response.body.rows[0].value_passwordhash), 'the passwordhash must not be null');
        assert(is.not.equal(value, response.body.rows[0].value_passwordhash), 'the passwordhash must be encrypted into database');
        assert(is.not.equal('', response.body.rows[0].value_passwordhash), 'the passwordhash must be encrypted into database and not empty');
      }
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

const checkDefaultPropertyPasswordHashedDatabase = (done, value) => {
  requestDB
    .get('/property/' + global.propertyvaluesid)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function (response) {
      assert(is.equal(1, response.body.count), 'must have only 1 property');
      if (value === null) {
        assert(is.null(response.body.rows[0].default_passwordhash), 'the passwordhash must be null');
      } else {
        assert(is.not.null(response.body.rows[0].default_passwordhash), 'the passwordhash must not be null');
        assert(is.not.equal(value, response.body.rows[0].default_passwordhash), 'the default passwordhash must be encrypted into database');
        assert(is.not.equal('', response.body.rows[0].default_passwordhash), 'the default passwordhash must be encrypted into database and not empty');
      }
    })
    .end(function (err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
};

exports.createItem = createItem;
exports.updateItem = updateItem;
exports.updateItemToDefault = updateItemToDefault;

exports.checkItemOkBoolean = checkItemOkBoolean;
exports.checkItemOkDate = checkItemOkDate;
exports.checkItemOkDatetime = checkItemOkDatetime;
exports.checkItemOkTime = checkItemOkTime;
exports.checkItemOkTypelink = checkItemOkTypelink;
exports.checkItemOkTypelinks = checkItemOkTypelinks;
exports.checkItemOkDecimal = checkItemOkDecimal;
exports.checkItemOkInteger = checkItemOkInteger;
exports.checkItemOkItemlink = checkItemOkItemlink;
exports.checkItemOkItemlinks = checkItemOkItemlinks;
exports.checkItemOkList = checkItemOkList;
exports.checkItemOkNumber = checkItemOkNumber;
exports.checkItemOkString = checkItemOkString;
exports.checkItemOkPassword = checkItemOkPassword;
exports.checkItemsOkPassword = checkItemsOkPassword;
exports.checkItemOkPropertylink = checkItemOkPropertylink;
exports.checkItemOkPasswordEncryptedDatabase = checkItemOkPasswordEncryptedDatabase;
exports.checkDefaultPropertyPasswordEncryptedDatabase = checkDefaultPropertyPasswordEncryptedDatabase;
exports.checkItemOkPasswordHashedDatabase = checkItemOkPasswordHashedDatabase;
exports.checkDefaultPropertyPasswordHashedDatabase = checkDefaultPropertyPasswordHashedDatabase;
