<?php
if (file_exists('./atk4-ide.phar')) {
    require_once "./atk4-ide.phar";
} elseif (file_exists('./atk4-ide') && is_dir('./atk4-ide')){
    include_once'vendor/atk4/atk4/loader.php';
    include_once'atk4-ide/init.php';
    include_once'atk4-ide/api/Controller/Config.php';
    include_once'atk4-ide/api/AgileToolkit/Installer.php';

} else {
    exit('Download atk4-ide.phar to use installer.');
}

$api=new AgileToolkit_Installer('new_atk4_install');
$api->main();
