<?php
chdir('..');
require_once '../vendor/autoload.php';
require_once 'lib/Cli.php';
$api=new Cli('cli');
$api->setArgv($argv);
$api->main();