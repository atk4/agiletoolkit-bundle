<?php
chdir('..');
require_once '../../vendor/autoload.php';
require_once 'lib/TestApi.php';
$api=new TestApi('test');
$api->main();

