<?php
if(file_exists('config.php')){
    header('Location: frontend/');
    exit;
}

include_once'vendor/atk4/atk4/loader.php';
include_once'atk4-ide/init.php';
include_once'atk4-ide/api/AgileToolkit/Installer.php';
$api=new AgileToolkit_Installer('new_atk4_install');
$api->main();
