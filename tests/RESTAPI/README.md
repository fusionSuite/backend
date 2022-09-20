# Run REST API tests

Need run mountebank fake server (HTTP, SMTP):

```
./node_modules/.bin/mb --configfile mountebank/imposters.ejs
```

Need run too php script to do actions on the database

```
php -S 127.0.0.1:8012 testDatabaseAccess.php
```

Then run the tests:

```
./node_modules/.bin/mocha --ignore "./node_modules/**/*.js" --ignore "./schemaValidation/**/*.js" "./**/*.js"
```


