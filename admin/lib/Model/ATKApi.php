<?php

class Model_ATKApi extends Model {

    public $base_url = "http://api.agiletoolkit.org/1/";

    function init() {
        parent::init();

        throw $this->exception('Obsolete Model_ATKApi','Obsolete');

        $hash = $this->api->sandbox->auto_config->getConfig('installation_hash');
        $this->auth = array('INSTALLATION', $hash);

        $this->setSource('RESTful', $this->base_url);
    }
}
