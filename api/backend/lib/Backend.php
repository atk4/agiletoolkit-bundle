<?php
class Backend extends ApiFrontend {
    function init() {
        parent::init();
        $this->addProjectLocations();
        $this->add('jUI');

    }
    function addProjectLocations() {
        $this->pathfinder->base_location->setBasePath(dirname(dirname(@$_SERVER['SCRIPT_FILENAME'])));
        $this->pathfinder->base_location->setBaseUrl($this->url('/'));
        $this->pathfinder->addLocation(
            array(
                'page'=>'page',
                'php'=>'../shared'
            )
        )->setBasePath($this->pathfinder->base_location->getPath());

    }
}
