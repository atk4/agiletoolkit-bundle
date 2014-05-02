<?php

class Model_ATKApi extends Model {

    public $base_url = "http://api.agiletoolkit.org/1/";
    public $auth = null;

    function init() {
        parent::init();

        $this->setSource('RESTful', $this->base_url);
    }
}
