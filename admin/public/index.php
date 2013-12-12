<?php
chdir('..');
require_once '../vendor/autoload.php';


if (file_exists('../atk4-ide.phar')) {
    require_once "../atk4-ide.phar";
}


require_once 'lib/Admin.php';
$api=new Admin('admin');
$api->main();
