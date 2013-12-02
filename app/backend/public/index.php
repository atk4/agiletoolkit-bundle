<?php
chdir('..');
require_once '../../vendor/autoload.php';
require_once 'lib/Backend.php';
$api=new Backend('backend');
$api->main();
