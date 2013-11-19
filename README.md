# Test project with new addon installer for version 4.3

## Install

    git clone git@github.com:rvadym/atk4-addons-test.git
    curl -s https://getcomposer.org/installer | php
    php composer.phar update

## Windows users
This Agile Toolkit setup requires symlinks to be able to access the default Agile Toolkit resources like images, css files etc. How to use symlinks on the Windows platform is described [here][1]. After, you should create the following symlink:

```
cd public/atk4
mklink ../../vendor/atk4/atk4/templates/ templates
```

[1]: http://www.howtogeek.com/howto/16226/complete-guide-to-symbolic-links-symlinks-on-windows-or-linux/
