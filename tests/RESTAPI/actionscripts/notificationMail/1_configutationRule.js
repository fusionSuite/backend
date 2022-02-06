const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

/**
* /v1/types endpoint
*/
describe('actionscripts/notificationMail - configuration rule', function() {

  it('get the types releted to mail notifications', function (done) {
    request
    .get('/v1/config/types')
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
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
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new SMTP configuration', function(done) {
    let properties = [];
    global.mailsmtpconfig.properties.forEach(prop => {
      if (prop.internalname === "action.notification.host") {
        properties.push({
          property_id: prop.id,
          value: '127.0.0.1'
        });
      }
      if (prop.internalname === "tcpport") {
        properties.push({
          property_id: prop.id,
          value: '10025'
        });
      }
      if (prop.internalname === "action.notification.encryption") {
        properties.push({
          property_id: prop.id,
          value: 'none'
        });
      }
      if (prop.internalname === "action.notification.sender.name") {
        properties.push({
          property_id: prop.id,
          value: 'John Rambo'
        });
      }
      if (prop.internalname === "action.notification.sender.email") {
        properties.push({
          property_id: prop.id,
          value: 'john@rambo.com'
        });
      }
    });
    request
    .post('/v1/items')
    .send({
      name: 'SMTP config',
      type_id: global.mailsmtpconfig.id,
      properties
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.mailsmtpconfigId = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a new mail notification configuration', function(done) {
    let properties = [];
    global.mailconfig.properties.forEach(prop => {
      if (prop.internalname === "action.notification.smtpconfig") {
        properties.push({
          property_id: prop.id,
          value: ''+global.mailsmtpconfigId
        });
      }
      if (prop.internalname === "action.classname") {
        properties.push({
          property_id: prop.id,
          value: 'notificationMail'
        });
      }
      if (prop.internalname === "action.associatedaction") {
        properties.push({
          property_id: prop.id,
          value: 'simpleNotification'
        });
      }
      if (prop.internalname === "action.notification.htmltemplate") {
        properties.push({
          property_id: prop.id,
          value: 'information'
        });
      }
      if (prop.internalname === "title") {
        properties.push({
          property_id: prop.id,
          value: 'New Laptop added'
        });
      }
      if (prop.internalname === "message") {
        properties.push({
          property_id: prop.id,
          value: 'A new laptop has been added into FusionSuite.'
        });
      }
    });
    request
    .post('/v1/items')
    .send({
      name: 'Simple mail notification config',
      type_id: global.mailconfig.id,
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
      global.mailconfigId = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  it('create a rule with mail notification', function(done) {
    request
    .post('/v1/rules/actionscript')
    .send({
      name: 'Notification new laptop',
      comment: 'simple notification by mail when create a new Laptop'
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
      global.ruleId = response.body.id;
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

  // it('create a criterium for the rule', function(done) {

  // });

  it('create an action for the rule', function(done) {
    request
    .post('/v1/rules/actionscript/' + global.ruleId + '/actions')
    .send({
      name: 'send simple notification',
      field: 'test',
      type: 'actionscript',
      values: global.mailconfigId,
      comment: ''  
    })
    .set('Accept', 'application/json')
    .set('Authorization', 'Bearer ' + global.token)
    .expect(200)
    .expect('Content-Type', /json/)
    .expect(function(response) {
      assert(is.propertyCount(response.body, 1));
      assert(is.integer(response.body.id));
      assert(validator.matches('' + response.body.id, /^\d+$/));
    })
    .end(function(err, response) {
      if (err) {
        return done(err + ' | Response: ' + response.text);
      }
      return done();
    });
  });

});
