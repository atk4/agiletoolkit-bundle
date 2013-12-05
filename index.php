<?php
if (file_exists('./atk4-ide.phar')) {
  include_once './atk4-ide.phar';
} elseif (file_exists('./atk4-ide') && is_dir('./atk4-ide')){
  include_once './atk4-ide/init.php';
} else {
    exit('Download atk4-ide.phar to use installer.');
}

if(file_exists('config.php')){
    header('Location: frontend/');
    exit;
}

include_once'vendor/atk4/atk4/loader.php';
include_once'atk4-ide/init.php';
include_once'atk4-ide/api/AgileToolkit/Installer.php';
$api=new AgileToolkit_Installer('new_atk4_install');
$api->main();
