#!/bin/bash

# Read versio
version=`cat vendor/atk4/atk4/VERSION`

# Clean up
rm -rf dist/agiletoolkit
mkdir -p dist/agiletoolkit

# Copy some stuff inside
cp -aR vendor dist/agiletoolkit/ 

cp -aR admin dist/agiletoolkit/
cp -aR frontend dist/agiletoolkit/
cp -aR shared dist/agiletoolkit/

# Next - remove .git folders to conserve space, not sure if it will kill composer
( cd dist/agiletoolkit/ && find -name .git | xargs rm -rf )

# Grab fresh certificate from agiletoolkit.org site
echo -n | openssl s_client -connect agiletoolkit.org:443 | sed -ne '/-BEGIN CERTIFICATE-/,/-END CERTIFICATE-/p' > dist/cert/agiletoolkit.cert

# Somehow create PHAR here


# Copy PHAR to distribution
#cp atk4-ide.phar dist/agiletoolkit

# Strip group write permssions as it makes people upset
( cd dist; chmod g-w -R agiletoolkit )

# tar, but make sure 
( cd dist; tar --no-same-owner --no-xattrs -czf agiletoolkit-${version}.tgz agiletoolkit/ )


# next , let's upload file to the server

