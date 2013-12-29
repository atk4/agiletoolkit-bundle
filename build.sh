#!/bin/bash 

# Read versio
version=`cat vendor/atk4/atk4/VERSION`

# Clean up
rm -rf dist/agiletoolkit
mkdir -p dist/agiletoolkit
mkdir -p dist/agiletoolkit/atk4-ide

# Copy some stuff inside
cp -aR vendor dist/agiletoolkit/ 

cp -aR admin dist/agiletoolkit/
cp -aR frontend dist/agiletoolkit/
#cp -aR shared dist/agiletoolkit/
cp -a config-auto.php dist/agiletoolkit/
cp -a config-default.php dist/agiletoolkit/
cp -aR atk4-ide/public dist/agiletoolkit/atk4-ide/
cp -a index.php dist/agiletoolkit/

# Next - remove .git folders to conserve space, not sure if it will kill composer
( cd dist/agiletoolkit/ && rm -rf .git )

# Grab fresh certificate from agiletoolkit.org site
echo -n | openssl s_client -connect agiletoolkit.org:443  | sed -ne '/-BEGIN CERTIFICATE-/,/-END CERTIFICATE-/p'  > dist/agiletoolkit/cert/agiletoolkit.cert

# create PHAR shell
rm -rf dist/tmp
mkdir -p dist/tmp
cp -aR agiletoolkit-sandbox/phar_skel/* dist/tmp
cp -aR agiletoolkit-sandbox/{lib,addons,template,init.php} dist/tmp/src
( cd dist/tmp; php create-phar.php )
#( cd _build/atk4_phar; php create-phar.php ) 

cp dist/tmp/agiletoolkit-sandbox.phar dist/agiletoolkit/
cp dist/tmp/agiletoolkit-sandbox.phar /www/agiletoolkit.org/public/dist/
#cp _build/atk4_phar/build/atk4-ide.phar dist/agiletoolkit/

# Strip group write permssions as it makes people upset
( cd dist; chmod g-w -R agiletoolkit )

# tar, but make sure 
( cd dist; tar --no-same-owner --no-xattrs -czf agiletoolkit-${version}.tgz \
  agiletoolkit/ && cp agiletoolkit-${version}.tgz /www/agiletoolkit.org/public/dist/ )


rm -rf dist

# next , let's upload file to the server

rm -rf /www/install-test/agiletoolkit/
( 
  cd /www/install-test/; 
  tar -zxf /www/agiletoolkit.org/public/dist/agiletoolkit-${version}.tgz 
  chmod 777 config-auto.php
)

# give rights so others can do it too
chmod -R g+w /www/install-test/agiletoolkit/

echo "Installed. Archive is http://www4.agiletoolkit.org/dist/agiletoolkit-${version}.tgz "
echo "Test: http://install-test-399482.agiletoolkit.org/agiletoolkit/"
