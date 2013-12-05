<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 12/5/13
 * Time: 12:01 PM
 * To change this template use File | Settings | File Templates.
 */

//chdir('atk4-ide');

require_once './vendor/autoload.php';
require_once './atk4-ide/api/AgileToolkit/Installer.php';

if (file_exists('./atk4-ide.phar')) {
  include_once './atk4-ide.phar';
} elseif (file_exists('./atk4-ide') && is_dir('./atk4-ide')){
  include_once './atk4-ide/init.php';
} else {
    exit('Download atk4-ide.phar to use installer.');
}



$api = new AgileToolkit_Installer('atl4-installer');
$api->main();