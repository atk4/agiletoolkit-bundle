<?php

chdir('../../..');
require'vendor/autoload.php';
$api=new Frontend('front');
$api->main();
