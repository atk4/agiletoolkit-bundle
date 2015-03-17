#!/bin/bash -e

# Read versio
version=`cat vendor/atk4/atk4/VERSION`

# Clean up
rm -rf dist/agiletoolkit
mkdir -p dist/agiletoolkit
#mkdir -p dist/agiletoolkit/atk4-ide

# Copy some stuff inside
cp -aR vendor dist/agiletoolkit/

cp -aR admin dist/agiletoolkit/
cp -aR frontend dist/agiletoolkit/
#cp -aR shared dist/agiletoolkit/
#cp -a config-auto.php dist/agiletoolkit/
cp -a config-default.php dist/agiletoolkit/
#cp -aR atk4-ide/public dist/agiletoolkit/atk4-ide/
cp -a index.php dist/agiletoolkit/
cp -a run.* dist/agiletoolkit/

# Next - remove .git folders to conserve space, not sure if it will kill composer
( cd dist/agiletoolkit/ && rm -rf .git )

# create PHAR shell
rm -rf dist/tmp
mkdir -p dist/tmp/src
mkdir -p dist/tmp/src/cert



#cp -aR agiletoolkit-sandbox/phar_skel/* dist/tmp
cp -aR agiletoolkit-sandbox/{lib,addons,template,init.php} dist/tmp/src


# Grab fresh certificate from agiletoolkit.org site
echo -n | openssl s_client -connect agiletoolkit.org:443  | sed -ne '/-BEGIN CERTIFICATE-/,/-END CERTIFICATE-/p'  > dist/tmp/src/cert/agiletoolkit.cert


{
  cat vendor/atk4/atk4/VERSION
  echo -n "Branch: "
  (cd vendor/atk4/atk4/; git rev-parse --abbrev-ref HEAD)
  echo "Build: `date '+%Y-%m-%d'`"

  echo -n "Sandbox Revision: "
  (cd agiletoolkit-sandbox/; git rev-list --count HEAD)

  echo -n "ATK4 Revision: "
  (cd vendor/atk4/atk4/; git rev-list --count HEAD)

  ver=`(cd agiletoolkit-css; cat framework/less/agiletoolkit.less | head -n10 | grep 'Agile Toolkit' | sed 's/.* v//')`
  rev=`(cd agiletoolkit-css; git rev-list --count HEAD)`
  echo "Agile CSS: $ver ($rev)"

} >> dist/agiletoolkit/VERSION

{
  cat agiletoolkit-sandbox/addons/sandbox/lib/Initiator.php | grep -A1 getVersion | tail -1 | cut -d"'" -f2
} >> dist/agiletoolkit/VERSION.Sandbox

cp dist/agiletoolkit/VERSION dist/tmp/src/VERSION
cp dist/agiletoolkit/VERSION.Sandbox dist/tmp/src/VERSION.Sandbox

( cd phar-packer/; php create-phar.php )
#( cd _build/atk4_phar; php create-phar.php )

#cp dist/tmp/agiletoolkit-sandbox.phar dist/agiletoolkit/
#cp dist/tmp/agiletoolkit-sandbox.phar /www/agiletoolkit.org/public/dist/
#cp _build/atk4_phar/build/atk4-ide.phar dist/agiletoolkit/

cp gitignore-distrib dist/agiletoolkit/.gitignore
cp composer.json dist/agiletoolkit/
cp composer.lock dist/agiletoolkit/
rm dist/agiletoolkit/vendor/atk4/atk4/.htaccess

# Todo - we should compile it here instead
cp -aR vendor/atk4/atk4/public/atk4 dist/agiletoolkit/admin/public/
cp -aR vendor/atk4/atk4/public/atk4 dist/agiletoolkit/frontend/public/

# Strip group write permssions as it makes people upset
( cd dist; chmod g-w -R agiletoolkit )

echo "cleaning .git from dist"

( cd dist && find -name .git | while read x; do rm -r $x; done )

# tar, but make sure
( cd dist; tar --no-same-owner --no-xattrs -czf agiletoolkit-${version}.tgz \
  agiletoolkit/ && cp agiletoolkit-${version}.tgz /www/agiletoolkit.org/public/dist/ && \
  cp agiletoolkit/agiletoolkit-sandbox.phar /www/agiletoolkit.org/public/dist/ )

cat dist/agiletoolkit/VERSION

rm -rf dist

# next , let's upload file to the server

rm -rf /www/install-test/agiletoolkit/
(
  cd /www/install-test/;
  tar -zxf /www/agiletoolkit.org/public/dist/agiletoolkit-${version}.tgz
  sudo chgrp upload -R .
)

# give rights so others can do it too
chmod -R g+w /www/install-test/agiletoolkit/

echo "Installed. Archive is http://www4.agiletoolkit.org/dist/agiletoolkit-${version}.tgz "
echo "Test: http://install-test-399482.agiletoolkit.org/agiletoolkit/"
