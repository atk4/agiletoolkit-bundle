<?php
if (file_exists('./atk4-ide.phar')) {
    require_once "phar://atk4-ide.phar/init.php";
    require_once'vendor/atk4/atk4/loader.php';
    require_once'phar://atk4-ide.phar/api/Controller/Config.php';
    require_once'phar://atk4-ide.phar/api/AgileToolkit/Installer.php';

} elseif (file_exists('./atk4-ide') && is_dir('./atk4-ide')){
    include_once'vendor/atk4/atk4/loader.php';
    include_once'atk4-ide/init.php';
    include_once'atk4-ide/api/Controller/Config.php';
    include_once'atk4-ide/api/AgileToolkit/Installer.php';

} else {
    exit('Download atk4-ide.phar to use installer.');
}

//if(file_exists('config.php')){
//    header('Location: frontend/');
//    exit;
//}
$api=new AgileToolkit_Installer('new_atk4_install');
$api->main();
