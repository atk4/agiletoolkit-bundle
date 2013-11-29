<?php
chdir('../../..');
require'vendor/autoload.php';
$api=new TestApi('test');
$api->main();

