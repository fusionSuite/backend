# Run REST API tests

Need run mountebank fake server (HTTP, SMTP):

```
./node_modules/.bin/mb --configfile mountebank/imposters.ejs
```


Then run the tests:

```
./node_modules/.bin/mocha --ignore "./node_modules/**/*.js" --ignore "./schemaValidation/**/*.js" "./**/*.js"
```


