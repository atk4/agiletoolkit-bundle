<?php
chdir('..');
require_once '../vendor/autoload.php';




if (file_exists('../atk4-ide.phar')) {
    require_once "phar://../atk4-ide.phar/init.php";
    require_once'phar://../atk4-ide.phar/api/Controller/Config.php';
    require_once'phar://../atk4-ide.phar/api/AgileToolkit/Installer.php';
}


require_once 'lib/Admin.php';
$api=new Admin('admin');
$api->main();
