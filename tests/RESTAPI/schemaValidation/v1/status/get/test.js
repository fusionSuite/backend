const commonSchema = require('../../../commonSchema.js');

var fs = require('fs');
var json = JSON.parse(fs.readFileSync('schemaValidation/v1/status/get/schema.json', 'utf-8'));

describe('Schema REST API | '+json.path+' | '+json.method, function() {

  describe('test the get status', function() {
    path = json.path;
    commonSchema.generateHeaders(json.parameters);
    commonSchema.generateData(json.parameters, {});
    commonSchema.generatePath(json.path, {});
    commonSchema.executeRequest(json.method, json.responses);
  });
});
