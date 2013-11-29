<?php

chdir('../../..');
require'vendor/autoload.php';
$api=new Backend('backend');
$api->main();
