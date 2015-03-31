<?php
class Frontend extends ApiFrontend {
    function init() {
        parent::init();

        $this->add('Layout_Centered');
        // you can also use Layout_Fluid
    }
}
