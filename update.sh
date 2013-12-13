#!/bin/bash


# First, get composer there
if [ -f composer.json ]; then
    if [ -f composer.phar ]; then
        echo "== STEP1 == Updating composer dependencies"
        php composer.phar update
    else
        echo "== STEP1 == Installing composer dependencies"
        curl -sS https://getcomposer.org/installer | php
        php composer.phar install

        # If there are some folders around, link them

        CSS=""
        [ -f ~/Sites/agiletoolkit-css ] && CSS="~/Sites/agiletoolkit-css"
        [ -f /var/www/agiletoolkit-css ] && CSS="/var/www/agiletoolkit-css"
        [ -f ~/www/agiletoolkit-css ] && CSS="~/www/agiletoolkit-css"

        if [ "$CSS" ]; then
            echo "HEY, I found CSS library in $CSS, so I'm going to use it"
            ln -fs $CSS .
        fi

        ATK=""
        [ -f ~/Sites/atk4 ] && CSS="~/Sites/atk4"
        [ -f ~/Sites/atk43 ] && CSS="~/Sites/atk43"
        if [ "$ATK" ]; then
            echo "HEY, I found ATK library in $ATK, so I'm going to link it into vendor/atk4/atk4"
            rm -rf vendor/atk4/atk4
            ln -fs $ATK vendor/atk4/atk4

            # Need to make sure composer is not getting upset about it
        fi

        # composer fucks up few things, so fix them
        ( cd vendor/atk4/atk4; git remote rm composer )
        ( cd vendor/atk4/atk4; git checkout 4.3 )
        ( cd vendor/atk4/atk4; git remote add composer git://github.com/atk4/atk4.git )
    fi
fi


cat dependencies | while read dir path; do
  if [ -d $dir ]; then
    echo " => Updating $dir"
    ( cd $dir; git pull )
  else
    echo " => Installing $dir from $path"
    mkdir -p $dir
    ( cd $dir; git clone $path . )
  fi
done
