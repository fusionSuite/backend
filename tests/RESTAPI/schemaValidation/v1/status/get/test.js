const commonSchema = require('../../../commonSchema.js');

const fs = require('fs');
const json = JSON.parse(fs.readFileSync('schemaValidation/v1/status/get/schema.json', 'utf-8'));
const path = json.path;
const method = json.method;

describe('Schema REST API | ' + path + ' | ' + method, function () {
  describe('test the get status', function () {
    it('generate the headers', function (done) {
      commonSchema.generateHeaders(done, json.parameters);
    });

    it('generate the data', function (done) {
      commonSchema.generateData(done, json.parameters, {});
    });

    it('generate the path: ' + path, function (done) {
      commonSchema.generatePath(done, path, {});
    });

    it('check the endpoint and the response', function (done) {
      commonSchema.executeRequest(done, json.method, json.responses);
    });
  });
});
