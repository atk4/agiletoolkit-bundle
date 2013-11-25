<?php
chdir('../..');
require'vendor/autoload.php';
$api=new TestApi('test_project');
$api->main();

