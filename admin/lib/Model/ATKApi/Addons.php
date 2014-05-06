<?php

class Model_ATKApi_Addons extends Model_ATKApi {

    public $collection_uri = 'plugins';
    public $element_uri    = 'plugins/{$id}';

    function init() {
        parent::init();

        $this->addField('name');
        $this->addField('namespace');
        $this->addField('type');
        $this->addField('user_id');
        $this->addField('is_paid');
        $this->addField('descr');
        $this->addField('version');
    }

}