<?php

require 'vendor/autoload.php';


$app = new \atk4\ui\App('Welcome to Agile Toolkit');
$app->initLayout('Admin');


/****************************************************************
 * You can now remove the text below and write your own Web App *
 *                                                              *
 * Thank you for trying out Agile Toolkit                       *
 ****************************************************************/


$app->layout->leftMenu->addItem(['Front-end demo', 'icon'=>'puzzle'], ['index']);
$app->layout->leftMenu->addItem(['Admin demo', 'icon'=>'dashboard'], ['admin']);

class TestClient extends \atk4\data\Model {
    function init() {
        parent::init();

        $this->addField('full_name');
        $this->addField('company');
        $this->addField('added', ['type'=>'date']);
        $this->addField('balance', ['type'=>'money']);
    }
}

session_start();
$db = new \atk4\data\Persistence_Array($_SESSION);

$app->layout->add(['CRUD', 'paginator'=>false])->setModel(new TestClient($db, 'test-client'));

