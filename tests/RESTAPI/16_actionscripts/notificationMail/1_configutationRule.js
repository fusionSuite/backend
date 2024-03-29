const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');
global.listIds = {};
global.htmltemplateIds = {};

describe('actionscripts/NotificationMail - configuration rule', function () {
  it('get the types releted to mail notifications', function (done) {
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
          if (element.internalname === 'ruleaction.mailnotification.smtpconfiguration') {
            global.mailsmtpconfig = element;
          }
          if (element.internalname === 'ruleaction.mailnotification.config') {
            global.mailconfig = element;
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

  it('create a new SMTP configuration', function (done) {
    const properties = [];
    global.mailsmtpconfig.properties.forEach(prop => {
      if (prop.internalname === 'action.notification.host') {
        properties.push({
          property_id: prop.id,
          value: '127.0.0.1',
        });
      }
      if (prop.internalname === 'tcpport') {
        properties.push({
          property_id: prop.id,
          value: 10025,
        });
      }
      if (prop.internalname === 'action.notification.sender.name') {
        properties.push({
          property_id: prop.id,
          value: 'John Rambo',
        });
      }
      if (prop.internalname === 'action.notification.sender.email') {
        properties.push({
          property_id: prop.id,
          value: 'john@rambo.com',
        });
      }
    });
    request
      .post('/v1/items')
      .send({
        name: 'SMTP config',
        type_id: global.mailsmtpconfig.id,
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
        global.mailsmtpconfigId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('Get the list of ids', function (done) {
    request
      .get('/v1/config/properties')
      .send()
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        global.listIds = {};
        global.htmltemplateIds = {};
        for (const property of response.body) {
          if (property.internalname === 'action.notification.associatedaction') {
            for (const value of property.listvalues) {
              global.listIds[value.value] = value.id;
            }
          }
          if (property.internalname === 'action.notification.htmltemplate') {
            for (const value of property.listvalues) {
              global.htmltemplateIds[value.value] = value.id;
            }
          }
        }
        assert(is.propertyCount(global.listIds, 2));
        assert(is.propertyCount(global.htmltemplateIds, 2));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a new mail notification configuration', function (done) {
    const properties = [];
    global.mailconfig.properties.forEach(prop => {
      if (prop.internalname === 'action.notification.smtpconfig') {
        properties.push({
          property_id: prop.id,
          value: global.mailsmtpconfigId,
        });
      }
      if (prop.internalname === 'action.notification.classname') {
        properties.push({
          property_id: prop.id,
          value: 'NotificationMail',
        });
      }
      if (prop.internalname === 'action.notification.associatedaction') {
        properties.push({
          property_id: prop.id,
          value: global.listIds.simpleNotification,
        });
      }
      if (prop.internalname === 'action.notification.htmltemplate') {
        properties.push({
          property_id: prop.id,
          value: global.htmltemplateIds.information,
        });
      }
      if (prop.internalname === 'title') {
        properties.push({
          property_id: prop.id,
          value: 'New Laptop added',
        });
      }
      if (prop.internalname === 'message') {
        properties.push({
          property_id: prop.id,
          value: 'A new laptop has been added into FusionSuite.',
        });
      }
    });
    request
      .post('/v1/items')
      .send({
        name: 'Simple mail notification config',
        type_id: global.mailconfig.id,
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
        global.mailconfigId = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('create a rule with mail notification', function (done) {
    request
      .post('/v1/rules/actionscript')
      .send({
        name: 'Notification new laptop',
        comment: 'simple notification by mail when create a new Laptop',
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
        name: 'send simple notification',
        field: 'test',
        type: 'actionscript',
        values: global.mailconfigId,
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
