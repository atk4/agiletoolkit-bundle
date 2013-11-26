<?php

chdir('..');
require'vendor/autoload.php';
$api=new Cli('cli');
$api->setArgv($argv);
$api->main();