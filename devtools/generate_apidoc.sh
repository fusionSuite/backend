# Compile by default
rm -fr ../documentation/*
cd apidoc 
./node_modules/.bin/apidoc -i ../../src -o ../../documentation/ -c apidoc.json
# Compile second time with own template (needed because some files not generated only with own like 'main.bundle.js' file
./node_modules/.bin/apidoc -i ../../src -o ../../documentation/ -c apidoc.json -t ../template/
# Generate schema json for schema tests from apidoc
./node_modules/.bin/apidoc-swagger-3 -i ../../src/ -o ../../tests/RESTAPI/schemaValidation/
cd ..
php _generateFileStructureTestSchemaRESTAPI.php
