const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
global.zabbix = {};
global.listIds = {};

describe('actionscripts/ActionZabbix - configuration rule', function () {
  it('get the types releted to Zabbix', function (done) {
    request
      .get('/v1/config/types')
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.array(response.body));
        response.body.forEach(element => {
          if (element.internalname === 'ruleaction.zabbix.apiconfiguration') {
            global.zabbix.apiconfiguration = element;
          }
          if (element.internalname === 'ruleaction.zabbix.templates') {
            global.zabbix.templates = element;
          }
          if (element.internalname === 'ruleaction.zabbix.macros') {
            global.zabbix.macros = element;
          }
          if (element.internalname === 'ruleaction.zabbix.createhost') {
            global.zabbix.createhost = element;
          }
        });
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new Zabbix API configuration', function (done) {
    const properties = [];
    global.zabbix.apiconfiguration.properties.forEach(prop => {
      if (prop.internalname === 'url') {
        properties.push({
          property_id: prop.id,
          value: 'http://127.0.0.1:10800/zabbix',
        });
      }
      if (prop.internalname === 'username') {
        properties.push({
          property_id: prop.id,
          value: 'zabbix',
        });
      }
      if (prop.internalname === 'password') {
        properties.push({
          property_id: prop.id,
          value: '56y89uhjrrfdgGtyf3',
        });
      }
    });
    request
      .post('/v1/items')
      .send({
        name: 'Zabbix API config',
        type_id: global.zabbix.apiconfiguration.id,
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
        global.zabbixAPIConfigId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a zabbix template Linux', function (done) {
    const properties = [];
    global.zabbix.templates.properties.forEach(prop => {
      if (prop.internalname === 'action.zabbix.templateid') {
        properties.push({
          property_id: prop.id,
          value: 45,
        });
      }
    });
    request
      .post('/v1/items')
      .send({
        name: 'Linux',
        type_id: global.zabbix.templates.id,
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
        global.zabbixTemplateLinuxId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a zabbix template MariaDB', function (done) {
    const properties = [];
    global.zabbix.templates.properties.forEach(prop => {
      if (prop.internalname === 'action.zabbix.templateid') {
        properties.push({
          property_id: prop.id,
          value: 105,
        });
      }
    });
    request
      .post('/v1/items')
      .send({
        name: 'MariaDB',
        type_id: global.zabbix.templates.id,
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
        global.zabbixTemplateMariadbId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a zabbix macro MariaDB port', function (done) {
    const properties = [];
    global.zabbix.macros.properties.forEach(prop => {
      if (prop.internalname === 'action.zabbix.macro.name') {
        properties.push({
          property_id: prop.id,
          value: '{$MYSQL.HOST}',
        });
      }
      if (prop.internalname === 'action.zabbix.macro.value') {
        properties.push({
          property_id: prop.id,
          value: '3307',
        });
      }
    });
    request
      .post('/v1/items')
      .send({
        name: 'MariaDB',
        type_id: global.zabbix.macros.id,
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
        global.zabbixMacroId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('Get the list of ids of actions', function (done) {
    request
      .get('/v1/config/properties')
      .send()
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.listIds = {};
        for (const property of response.body) {
          if (property.internalname === 'action.zabbix.associatedaction') {
            for (const value of property.listvalues) {
              global.listIds[value.value] = value.id;
            }
          }
        }
        assert(is.propertyCount(global.listIds, 3));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a config for create host in Zabbix', function (done) {
    const properties = [];
    global.zabbix.createhost.properties.forEach(prop => {
      if (prop.internalname === 'action.zabbix.apiconfiguration') {
        properties.push({
          property_id: prop.id,
          value: global.zabbixAPIConfigId,
        });
      }
      if (prop.internalname === 'action.zabbix.classname') {
        properties.push({
          property_id: prop.id,
          value: 'ActionZabbix',
        });
      }
      if (prop.internalname === 'action.zabbix.associatedaction') {
        properties.push({
          property_id: prop.id,
          value: global.listIds.addHost,
        });
      }

      if (prop.internalname === 'action.zabbix.hostnameid') {
        properties.push({
          property_id: prop.id,
          value: 0,
        });
      }
      // if (prop.internalname === 'ip') {
      //   properties.push({
      //     property_id: prop.id,
      //     value: '0'
      //   });
      // }
      if (prop.internalname === 'action.zabbix.groupid') {
        properties.push({
          property_id: prop.id,
          value: 19,
        });
      }
      if (prop.internalname === 'action.zabbix.hostid') {
        properties.push({
          property_id: prop.id,
          value: null,
        });
      }
      if (prop.internalname === 'action.zabbix.templates') {
        properties.push({
          property_id: prop.id,
          value: [global.zabbixTemplateLinuxId],
        });
        // TODO not possible for the moment to have 2 'same' property
        // properties.push({
        //   property_id: prop.id,
        //   value: glo.zabbixTemplateMariadbId
        // });
      }
      if (prop.internalname === 'action.zabbix.macros') {
        properties.push({
          property_id: prop.id,
          value: [global.zabbixMacroId],
        });
      }
    });
    request
      .post('/v1/items')
      .send({
        name: 'Zabbix add host',
        type_id: global.zabbix.apiconfiguration.id,
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
        global.zabbixId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a rule with Zabbix create host', function (done) {
    request
      .post('/v1/rules/actionscript')
      .send({
        name: 'Add host into Zabbix',
        comment: 'Create a host into Zabbix (monitoring) when create a new Laptop',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
        global.ruleId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  // it('create a criterium for the rule', function (done) {

  // });

  it('create an action for the rule', function (done) {
    request
      .post('/v1/rules/actionscript/' + global.ruleId + '/actions')
      .send({
        name: 'Zabbix creation',
        field: 'test',
        type: 'actionscript',
        values: global.zabbixId,
        comment: '',
      })
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.propertyCount(response.body, 1));
        assert(is.integer(response.body.id));
        assert(validator.matches('' + response.body.id, /^\d+$/));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
