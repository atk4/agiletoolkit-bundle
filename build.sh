#!/bin/bash -e

version=`cat composer.lock | jq '.packages[] | select (.name == "atk4/ui").version' -r `

composer update

# Clean up
rm -rf dist/agiletoolkit
mkdir -p dist/agiletoolkit
#mkdir -p dist/agiletoolkit/atk4-ide

# Copy some stuff inside
cp -aR vendor dist/agiletoolkit/

cp -a admin.php dist/agiletoolkit/
cp -a index.php dist/agiletoolkit/

# Launch apps
cp -aR Agile\ Toolkit.app dist/agiletoolkit/
cp -a run.sh dist/agiletoolkit/
cp -a run.bat dist/agiletoolkit/
cp -a .DS_Store dist/agiletoolkit/

cp -a .gitignore dist/agiletoolkit/

# tar, but make sure
( cd dist; tar --no-same-owner -czf agiletoolkit-${version}.tgz \
    agiletoolkit/ && rm -r agiletoolkit )

#cp dist/agiletoolkit-${version}.tgz /www/agiletoolkit.org/public/dist/

# next , let's upload file to the server

#rm -rf /www/install-test/agiletoolkit/
#(
#  cd /www/install-test/;
#  tar -zxf /www/agiletoolkit.org/public/dist/agiletoolkit-${version}.tgz
#  sudo chgrp upload -R .
#)

# give rights so others can do it too
#chmod -R g+w /www/install-test/agiletoolkit/

echo "Ready! dist/agiletoolkit-${version}.tgz (`du -h dist/agiletoolkit-${version}.tgz | awk '{print $1}'`)"
