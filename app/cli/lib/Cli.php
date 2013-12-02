<?php
class Cli extends ApiCLI {
    public $argv;
    function _beforeInit() {
        $this->pm = $this->add('Controller_PageManager');
        parent::_beforeInit();
    }
    function init() {
        parent::init();
    }
    // console script args
    function setArgv($argv) {
        $this->argv = $argv;
    }
    function main(){
        var_dump($this->argv);
    }
}
