define({ "api": [
  {
    "type": "post",
    "url": "/v1/token",
    "title": "POST - Request a JWT token for autentication",
    "name": "PostToken",
    "group": "Autentication",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Request body - option 1": [
          {
            "group": "Request body - option 1",
            "type": "String",
            "optional": false,
            "field": "login",
            "description": "<p>The username to login.</p>"
          },
          {
            "group": "Request body - option 1",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>The password of the login.</p>"
          }
        ],
        "Request body - option 2": [
          {
            "group": "Request body - option 2",
            "type": "String",
            "optional": false,
            "field": "refresh_token",
            "description": "<p>The token (refresh_token) sent previously when you post in this endpoint to get the token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"login\": \"david\",\n  \"password\": \"xxxxxx\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>The token string.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "refresh_token",
            "description": "<p>The token string can be used to refresh / regenerate a new token when this token expire.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "expires",
            "description": "<p>The expiration timestamp.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODMxMzQ0NzIsImV4cCI6MTU4MzIyMDg3MiwianRpIjoiNE5odXg0RWY5WmdEVk9FVXRDNFg2ViIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdfQ.m4qf3e9M3Nwrl5A3wCrZ2l84HO1wB3d4oJr_1ZekYVk\",\n  \"refresh_token\": \"zE6vnZIyeWubw1X1toEbZ2yErdK9f5oYbcuFxzSf\",\n  \"expires\": 1583220872\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "DataNotConform",
            "description": "<p>The data sent are not conform.</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "LoginError",
            "description": "<p>The authentication can't be processed because login or password invalid.</p>"
          },
          {
            "group": "Error 401",
            "optional": false,
            "field": "LoginErrorrefresh",
            "description": "<p>The authentication can't be processed because refresh_token invalid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "(Error 400) Error-Response:",
          "content": "HTTP/1.1 400 Bad Request\n{\n  \"status\": \"error\",\n  \"message\": \"Missing request body, check the documentation\"\n}",
          "type": "json"
        },
        {
          "title": "(Error 401) Error-Response:",
          "content": "HTTP/1.1 401 Unauthorized\n{\n  \"status\": \"error\",\n  \"message\": \"Error when authentication, login or password not right\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../src/v1/Controllers/Token.php",
    "groupTitle": "Autentication"
  },
  {
    "type": "get",
    "url": "/v1/cmdb/items/:type",
    "title": "GET - Get all items of CMDB with type defined",
    "name": "GetCMDBItems",
    "group": "CMDBItems",
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>The id of the item.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The name of the item.</p> <p><code>type_id</code> int(11) NOT NULL,</p>"
          },
          {
            "group": "Success 200",
            "type": "Object|null",
            "optional": false,
            "field": "user",
            "description": "<p>The user information have created the ticket.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "user.id",
            "description": "<p>id of the user.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.login",
            "description": "<p>Login of the user.</p>"
          },
          {
            "group": "Success 200",
            "type": "String|null",
            "optional": false,
            "field": "user.firstname",
            "description": "<p>Firstname of the user.</p>"
          },
          {
            "group": "Success 200",
            "type": "String|null",
            "optional": false,
            "field": "user.lastname",
            "description": "<p>Lastname of the user.</p>"
          },
          {
            "group": "Success 200",
            "type": "String[]",
            "optional": false,
            "field": "user.displayname",
            "description": "<p>User displayname. // TODO <code>owner_group_id</code> // TODO  <code>state_id</code></p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>Date of the item creation.</p>"
          },
          {
            "group": "Success 200",
            "type": "String|null",
            "optional": false,
            "field": "updated_at",
            "description": "<p>Date of the last item modification.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "properties",
            "description": "<p>properties of the item</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "properties.name",
            "description": "<p>property name</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "properties.value",
            "description": "<p>property value</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n[\n  {\n    \"id\": 45,\n    \"name\": \"LP-000345\",\n    \"user: null,\n    \"created_at\": \"2020-07-20 14:30:45\",\n    \"updated_at\": null,\n    \"properties\": [\n      {\n        \"name\": \"Serial number\",\n        \"value\": \"gt43bf87d23d\"\n      },\n      {\n        \"name\": \"Model\",\n        \"value\": \"Latitude E7470\"\n      },\n      {\n        \"name\": \"Manufacturer\",\n        \"value\": \"Dell\"\n      }\n    ]\n  }\n]",
          "type": "json"
        }
      ]
    },
    "filename": "../src/v1/Controllers/CMDB/Item.php",
    "groupTitle": "CMDBItems",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/v1/cmdb/items/:typeid/items",
    "title": "POST - Create a new items",
    "name": "PostCMDBItems",
    "group": "CMDBItems",
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The name of the item.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "properties",
            "description": "<p>List of properties</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "properties.property_id",
            "description": "<p>The id of the property.</p>"
          },
          {
            "group": "Success 200",
            "type": "String[]",
            "optional": false,
            "field": "properties.value",
            "description": "<p>The value of the property for the item.</p>"
          }
        ]
      }
    },
    "parameter": {
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"name\": \"LP-000345\",\n  \"properties: [\n    {\n      \"property_id\": 3,\n      \"value\": \"gt43bf87d23d\"\n    },\n    {\n      \"property_id\": 8,\n      \"value\": \"Latitude E7470\"\n    },\n    {\n      \"property_id\": 5,\n      \"value\": \"Dell\"\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../src/v1/Controllers/CMDB/Item.php",
    "groupTitle": "CMDBItems",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/v1/cmdb/typeproperty",
    "title": "GET - Get all typeproperties",
    "name": "GetCMDBTypeProperties",
    "group": "CMDBTypeproperties",
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>The id of the typeproperty.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The name of the typeproperty.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "allowedValues": [
              "\"string\"",
              "\"integer\"",
              "\"float\"",
              "\"date\"",
              "\"datetime\"",
              "\"list\"",
              "\"boolean\""
            ],
            "optional": false,
            "field": "valuetype",
            "description": "<p>The type of value.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "regexformat",
            "description": "<p>The regexformat to verify the value is conform (works only with valuetype is string or list).</p>"
          },
          {
            "group": "Success 200",
            "type": "String[]|null",
            "optional": false,
            "field": "listvalues",
            "description": "<p>The list of values when valuetype=&quot;list&quot;, else null.</p>"
          },
          {
            "group": "Success 200",
            "type": "String|null",
            "optional": false,
            "field": "unit",
            "description": "<p>The unit used for the property (example: Ko, seconds...).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>Date of the item creation.</p>"
          },
          {
            "group": "Success 200",
            "type": "String|null",
            "optional": false,
            "field": "updated_at",
            "description": "<p>Date of the last item modification.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n[\n  {\n    \"id\": 8,\n    \"name\": \"Serial Number\",\n    \"valuetype\": \"string\",\n    \"listvalues\": null,\n    \"unit\": null,\n    \"created_at\": \"2020-07-21 09:21:52\",\n    \"updated_at\": null,\n  },\n  {\n    \"id\": 9,\n    \"name\": \"Model\",\n    \"valuetype\": \"list\",\n    \"listvalues\": [\"Latitude E7470\", \"Latitude E7490\", \"Latitude E9510\", \"P43s\"],\n    \"unit\": null,\n    \"created_at\": \"2020-07-21 09:31:30\",\n    \"updated_at\": null,\n  }\n]",
          "type": "json"
        }
      ]
    },
    "filename": "../src/v1/Controllers/CMDB/TypeProperty.php",
    "groupTitle": "CMDBTypeproperties",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/v1/cmdb/typeproperty",
    "title": "POST - Create a typeproperty",
    "name": "GetCMDBTypeProperties",
    "group": "CMDBTypeproperties",
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The name of the type of items.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "allowedValues": [
              "\"string\"",
              "\"integer\"",
              "\"float\"",
              "\"date\"",
              "\"datetime\"",
              "\"list\"",
              "\"boolean\""
            ],
            "optional": false,
            "field": "valuetype",
            "description": "<p>The TODO.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "regexformat",
            "description": "<p>The regexformat to verify the value is conform (works only with valuetype is string or list).</p>"
          },
          {
            "group": "Success 200",
            "type": "String[]|null",
            "optional": false,
            "field": "listvalues",
            "description": "<p>The TODO.</p>"
          }
        ],
        "Optional": [
          {
            "group": "Optional",
            "type": "String|null",
            "optional": false,
            "field": "unit",
            "description": "<p>The TODO.</p>"
          }
        ]
      }
    },
    "filename": "../src/v1/Controllers/CMDB/TypeProperty.php",
    "groupTitle": "CMDBTypeproperties",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/v1/cmdb/type",
    "title": "GET - Get all types of items in the CMDB",
    "name": "GetCMDBTypes",
    "group": "CMDBTypes",
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>The id of the item.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The name of the item.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "created_at",
            "description": "<p>Date of the item creation.</p>"
          },
          {
            "group": "Success 200",
            "type": "String|null",
            "optional": false,
            "field": "updated_at",
            "description": "<p>Date of the last item modification.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n[\n  {\n    \"id\": 23,\n    \"name\": \"Memory\",\n    \"created_at\": \"2020-07-20 22:15:08\",\n    \"updated_at\": null,\n  }\n]",
          "type": "json"
        }
      ]
    },
    "filename": "../src/v1/Controllers/CMDB/Type.php",
    "groupTitle": "CMDBTypes",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "patch",
    "url": "/v1/cmdb/type/:id",
    "title": "PATCH - Update an existing type of items",
    "name": "PatchCMDBTypes",
    "group": "CMDBTypes",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Unique ID of the type.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the type.</p>"
          }
        ]
      }
    },
    "filename": "../src/v1/Controllers/CMDB/Type.php",
    "groupTitle": "CMDBTypes",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "patch",
    "url": "/v1/cmdb/type/:id",
    "title": "PATCH - Update an existing type of items",
    "name": "PatchCMDBTypes",
    "group": "CMDBTypes",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Unique ID of the type.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the type.</p>"
          }
        ]
      }
    },
    "filename": "../src/v1/Controllers/CMDB/TypeProperty.php",
    "groupTitle": "CMDBTypes",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/v1/cmdb/type",
    "title": "POST - Create a new type of items",
    "name": "PostCMDBTypes",
    "group": "CMDBTypes",
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The name of the type of items.</p>"
          }
        ]
      }
    },
    "filename": "../src/v1/Controllers/CMDB/Type.php",
    "groupTitle": "CMDBTypes",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/v1/cmdb/type/:id/property/:propertyid",
    "title": "POST - Associate a property of this type",
    "name": "PostCMDBTypesProperty",
    "group": "CMDBTypes",
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The name of the type of items.</p>"
          }
        ]
      }
    },
    "filename": "../src/v1/Controllers/CMDB/Type.php",
    "groupTitle": "CMDBTypes",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/v1/rules/:type/:id",
    "title": "GET - Get one rule",
    "name": "GetRule",
    "group": "Rules",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"searchitem\"",
              "\"rewritefield\"",
              "\"notification\""
            ],
            "optional": false,
            "field": "type",
            "description": "<p>The type of the rules.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Rule unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The name of the item.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "comment",
            "description": "<p>The comment of the item.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "criteria",
            "description": "<p>The criteria list.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "criteria.id",
            "description": "<p>The criteria id.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "allowedValues": [
              "\\d+\\.\\d+"
            ],
            "optional": false,
            "field": "criteria.field",
            "description": "<p>The field of the criteria. The format is type_id.property_id</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "allowedValues": [
              "\"=\"",
              "\">\"",
              "\"<\"",
              "\"!=\"",
              "\"in\"",
              "\"contain\""
            ],
            "optional": false,
            "field": "criteria.comparator",
            "description": "<p>The comparator.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "criteria.values",
            "description": "<p>The value(s) to check.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "criteria.comment",
            "description": "<p>The criteria comment.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "actions",
            "description": "<p>The actions list.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "actions.id",
            "description": "<p>The action id.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "allowedValues": [
              "\\d+\\.\\d+|null"
            ],
            "optional": false,
            "field": "actions.field",
            "description": "<p>The field to update. The format is type_id.property_id</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "allowedValues": [
              "\"replace\"",
              "\"append\"",
              "\"notimport\"",
              "\"sendnotification\""
            ],
            "optional": false,
            "field": "actions.type",
            "description": "<p>The type of action.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "actions.values",
            "description": "<p>The rewritten value.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "actions.comment",
            "description": "<p>The criteria comment.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"name\": \"Rewrite wrong serial number\",\n  \"comment\": \"On some devices, the serial is not the right (for example '123456')\",\n  \"criteria\": [\n    {\n      \"id\": 12,\n      \"field\": \"computer.serialnumber\",\n      \"comparator\": \"\",\n      \"values\": \"\",\n      \"comment\": \"\"\n    }\n  ],\n  \"actions: [\n    {\n      \"id\": 12,\n      \"field\": \"computer.serialnumber\",\n      \"type\": \"replace\",\n      \"values\": \"\",\n      \"comment\": \"Replace by empty value\"\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "../src/v1/Controllers/Rule.php",
    "groupTitle": "Rules",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "(Error 401) Error-Response:",
          "content": "HTTP/1.1 401 Not Found\n{\n    \"status\": \"error\",\n    \"message\": \"Signature verification of the token failed\"\n}",
          "type": "json"
        }
      ]
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "get",
    "url": "/v1/rules/:type",
    "title": "GET - Get all rules with type defined",
    "name": "GetRules",
    "group": "Rules",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"searchitem\"",
              "\"rewritefield\"",
              "\"notification\""
            ],
            "optional": false,
            "field": "type",
            "description": "<p>The type of the rules.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>The id of the item.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>The name of the item.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "comment",
            "description": "<p>The comment of the item.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n[\n  {\n    \"id\": 23,\n    \"name\": \"Rewrite wrong serial number\",\n    \"comment\": \"On some devices, the serial is not the right (for example '123456')\"\n  }\n]",
          "type": "json"
        }
      ]
    },
    "filename": "../src/v1/Controllers/Rule.php",
    "groupTitle": "Rules",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/v1/rules/:type",
    "title": "POST - Create a new rule",
    "name": "PostRule",
    "group": "Rules",
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the rule.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "comment",
            "description": "<p>Comment of the rule.</p>"
          }
        ]
      }
    },
    "filename": "../src/v1/Controllers/Rule.php",
    "groupTitle": "Rules",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/v1/rules/:type/:id/action",
    "title": "POST - Create a new action for the rule",
    "name": "PostRuleActions",
    "group": "Rules",
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the rule.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "comment",
            "description": "<p>Comment of the rule.</p>"
          }
        ]
      }
    },
    "filename": "../src/v1/Controllers/Rule.php",
    "groupTitle": "Rules",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  },
  {
    "type": "post",
    "url": "/v1/rules/:type/:id/criteria",
    "title": "POST - Create a new criteria for the rule",
    "name": "PostRuleCriteria",
    "group": "Rules",
    "version": "1.0.0",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the rule.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "comment",
            "description": "<p>Comment of the rule.</p>"
          }
        ]
      }
    },
    "filename": "../src/v1/Controllers/Rule.php",
    "groupTitle": "Rules",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "AutorizationFailure",
            "description": "<p>The JWT token is not valid.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>The JWT token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example",
          "content": "\"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODUxMjg3NzUsImV4cCI6MTU4NTIxNTE3NSwianRpIjoiNGwyYlZFVmF2VlpNaDdOZWlFSXVMQyIsInN1YiI6IiIsInNjb3BlIjpbInJlYWQiLCJ3cml0ZSIsImRlbGV0ZSJdLCJ1c2VyX2lkIjoyLCJmaXJzdG5hbWUiOm51bGwsImxhc3RuYW1lIjpudWxsLCJhcGl2ZXJzaW9uIjoidjEifQ.prsGpbZbQRlA9JTkgLLSbjOSZDhtjrTLmPPpxUhRMXs\"",
          "type": "Header"
        }
      ]
    }
  }
] });
