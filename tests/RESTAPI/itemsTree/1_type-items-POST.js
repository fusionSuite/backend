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

  // Create a new type (no need property), with tree = true

  // create a new item (parent)

  // create a new item (child)

  // create a new item most top level but error because have yet one

});
