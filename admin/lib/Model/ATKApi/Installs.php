<?php

class Model_ATKApi_Installs extends Model_ATKApi {

    public $collection_uri = 'installs';
    public $element_uri    = 'installs/{$id}';

    public $id_field = 'installation_hash';

    function init() {
        parent::init();

        $this->addField('installation_hash');
        $this->addField('name');
        $this->addField('category');
        $this->addField('type');
        $this->addField('user_id');
        $this->addField('city');
        $this->addField('country');
        $this->addField('os_short');
        $this->addField('cert_issued_dts')->type('datetime');
        $this->addField('cert_expires_dts')->type('datetime');
    }

}