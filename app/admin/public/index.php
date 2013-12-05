<?php
chdir('..');
require_once '../../vendor/autoload.php';

// Enable Development Mode
if (file_exists('../../atk4-ide.phar')) {
    include_once '../../atk4-ide.phar';
} elseif (file_exists('../../atk4-ide') && is_dir('../../atk4-ide')){
    include_once '../../atk4-ide/index.php';
}

require_once 'lib/Admin.php';
$api=new Admin('admin');
$api->main();
