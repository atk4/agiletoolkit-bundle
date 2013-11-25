<?php

chdir('../..');
require'vendor/autoload.php';
$api=new Admin('admin');
$api->main();