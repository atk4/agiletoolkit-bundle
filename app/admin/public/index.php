<?php
chdir('..');
require_once '../../vendor/autoload.php';

// Enable Development Mode
if(file_exists('../../atk-ide.phar')) {
  include_once '../../atk-ide.phar';
}elseif(file_exists('../../atk-ide') && is_dir('../../atk-ide')){
  include_once '../../atk-ide/index.php';
}

require_once 'lib/Admin.php';
$api=new Admin('admin');
$api->main();
