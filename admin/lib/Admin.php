<?php

class Admin extends App_Admin {

    function init() {
        parent::init();

        $this->api->menu->addItem('Dashboard', '/');
    }
}
