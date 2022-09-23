const supertest = require('supertest');
const validator = require('validator');
const assert = require('assert');
const is = require('is_js');

const request = supertest('http://127.0.0.1/fusionsuite/backend');

describe('roles | grant | attach the role to user', function () {
  it('create a user (user1), will be used to test the permissions', function (done) {
    request
      .post('/v1/items')
      .send({
        name: 'user1',
        type_id: 2,
        properties: [
          {
            property_id: 5,
            value: 'test',
          },
        ],
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
        global.user1 = response.body.id;
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('attach user to the role', function (done) {
    request
      .post('/v1/config/roles/' + global.roleId + '/user/' + global.user1)
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
  });

  it('get the role and check if user associated', function (done) {
    request
      .get('/v1/config/roles/' + global.roleId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.array(response.body.users));
        assert(is.equal('user1', response.body.users[0].name));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });

  it('attach admin to the role', function (done) {
    request
      .post('/v1/config/roles/' + global.roleId + '/user/2')
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
  });

  it('get the role and check if the 2 users are associated', function (done) {
    request
      .get('/v1/config/roles/' + global.roleId)
      .set('Accept', 'application/json')
      .set('Authorization', 'Bearer ' + global.token)
      .expect(200)
      .expect('Content-Type', /json/)
      .expect(function (response) {
        assert(is.not.empty(response.body));
        assert(is.array(response.body.users));

        assert(is.equal(4, Object.keys(response.body.created_by).length), 'created_by must have 4 attributes: id, name, first_name, last_name');
        assert(is.equal(2, response.body.created_by.id, 'created_by must be filled with admin id'));
        assert(is.equal('admin', response.body.created_by.name, 'created_by.name must be filled with `admin'));
        assert(is.equal('Steve', response.body.created_by.first_name, 'created_by.first_name must be `Steve`'));
        assert(is.equal('Rogers', response.body.created_by.last_name, 'created_by.last_name must be `Rogers`'));

        assert(is.equal(2, response.body.users.length), 'Must have 2 users associated');

        assert(is.equal(4, Object.keys(response.body.users[0]).length), 'users[0] must have 4 attributes: id, name, first_name, last_name');
        assert(is.equal(global.user1, response.body.users[0].id, 'users[0] must be filled with user1 id'));
        assert(is.equal('user1', response.body.users[0].name, 'users[0].name must be filled with `user1'));
        assert(is.equal('', response.body.users[0].first_name, 'users[0].first_name must be empty'));
        assert(is.equal('', response.body.users[0].last_name, 'users[0].last_name must be empty'));

        assert(is.equal(4, Object.keys(response.body.users[1]).length), 'users[1] must have 4 attributes: id, name, first_name, last_name');
        assert(is.equal(2, response.body.users[1].id, 'users[1] must be filled with admin user id'));
        assert(is.equal('admin', response.body.users[1].name, 'users[1].name must be filled with `admin'));
        assert(is.equal('Steve', response.body.users[1].first_name, 'users[1].first_name must be filled with `Steve`'));
        assert(is.equal('Rogers', response.body.users[1].last_name, 'users[1].last_name must be filled with `Rogers`'));
      })
      .end(function (err, response) {
        if (err) {
          return done(err + ' | Response: ' + response.text);
        }
        return done();
      });
  });
});
