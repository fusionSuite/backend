const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');
const { faker } = require('@faker-js/faker');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

function checkResponse() {


}

function methodPost(path, headers, data, responses) {
  return request
  .post(path);
}

function methodGet(path, headers, data, responses) {
  return request
  .get(path);
}

function methodPatch() {
  return request
  .patch(path);
}

function methodDelete() {
  return request
  .delete(path);
}

function checkResponse(response, responses) {
  if (Object.keys(responses).length === 1 && response.id === undefined) {
    stop = false;
    for (const [property, responseData] of Object.entries(responses)) {
      if (responseData.type !== 'array') {
        // it's not an array, so use normal check
      } else {
        assert(is.array(response), 'response property '+property+' not an array');
        for (let item of response) {
          checkResponse(item, responseData.items.properties);
          stop = true;
        }
      }
    }
    if (stop) {
      return;
    }
  }
  assert(is.propertyCount(response, Object.keys(responses).length), 'The response property count not right: ['+Object.keys(response).toString()+'] => ['+Object.keys(responses).toString()+']');
  for (const [property, responseData] of Object.entries(responses)) {
    // manage null value
    type = responseData.type;
    typeSpl = type.split("|");
    if (typeSpl.length === 2) {
      if (typeSpl[0] === 'null') {
        if (response[property] === null) {
          continue;
        }
      }
      type = typeSpl[1];
    }

    if (type == 'string') {
      assert(is.string(response[property]), 'The response property `'+property+'` not a string: '+response[property]);
    } else if (type == 'number') {
      assert(is.number(response[property]), 'response property '+property+' not a number');
    } else if (type == 'boolean') {
      assert(is.boolean(response[property]), 'response property '+property+' not a boolean');
    } else if (type == 'iso8601') {
      assert(validator.isISO8601(response[property]), 'response property '+property+' not a date at format ISO8601');
    } else if (type == 'array') {
      assert(is.array(response[property]), 'response property '+property+' not an array');
      for (let item of response[property]) {
        if (responseData.items.type === 'object') {
          checkResponse(item, responseData.items.properties);
        } else { // it's array
          if (responseData.items.type == 'string') {
            assert(is.string(item), 'The response property `'+property+'` not a string: '+item);
          } else if (responseData.items.type == 'number') {
            assert(is.number(item), 'response property '+property+' not a number');
          }
        }
      }
    } else if (type == 'object') {
      assert(is.object(response[property]), 'response property '+property+' not an object');
      checkResponse(response[property], responseData.properties);
    } else if (type == 'any') {
      // can by any type, so not test it
    } else if (type == 'string[]') {
      assert(is.array(response[property]), 'response property '+property+' not an array');
      for (let item of response[property]) {
        assert(is.string(item), 'The response property '+property+' not a string');
      }
    } else {
      assert(false, 'response property type '+type+' or property '+property+' not supported in the test');
    }
  }
}

const executeRequest = (method, responses) => {
  it('check the endpoint and the response', function(done) {
    if (method === 'get') {
      req = methodGet(global.path, global.headers, global.data, responses);
    } else if (method === 'post') {
      req = methodPost(global.path, global.headers, global.data, responses);
    } else if (method === 'patch') {
      req = methodPatch(global.path, global.headers, global.data, responses);
    } else if (method === 'delete') {
      req = methodDelete(global.path, global.headers, global.data, responses);
    }
    req
    .set(headers)
    .send(global.data)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      checkResponse(response.body, responses);
      if (method === 'post') {
        global.id = response.body.id;
      }
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });

  });
};

const generateHeaders = (parameters) => {
  it('generate the headers', function(done) {
    global.headers = {
      'Accept': 'application/json'
    };
    for(let params of parameters) {
      if (params.in == 'header') {
        value = '';
        if (params.name === 'Authorization') {
          value = 'Bearer '+global.token;
        }
        global.headers[params.name] = value;
      }
    }
    return done();
  });
};

const generateData = (parameters, mapping) => {
  it('generate the data', function(done) {
    global.data = {};
    for(let params of parameters) {
      if (params.required === false && mapping[params.name] === undefined) {
        continue;
      }
      if (params.in === 'body') {
        for (let [name, property] of Object.entries(params.schema.properties)) {
          value = null;
          if (mapping[name] !== undefined) {
            value = mapping[name];
          } else if (!params.schema.required.includes(name)) {
            continue;
          } else if (property.type === 'string') {
            value = faker.random.word();
          }
          global.data[name] = value;
        }
      }
    }
    return done();
  });
};

const generatePath = (path, mapping) => {
  it('generate the path: '+path, function(done) {
    global.path = path;
    for (const [key, value] of Object.entries(mapping)) {
      global.path = global.path.replace('{'+key+'}', global[value]);
    }
    return done();
  });
};

const copyGlobalValue = (attributeSource, attributeDestination) => {
  it('copy global '+attributeSource+' to '+attributeDestination, function(done) {
    global[attributeDestination] = global[attributeSource];
    return done();
  });
};

exports.executeRequest = executeRequest;
exports.generateHeaders = generateHeaders;
exports.generateData = generateData;
exports.generatePath = generatePath;
exports.copyGlobalValue = copyGlobalValue;
