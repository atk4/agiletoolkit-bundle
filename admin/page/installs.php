<?php
/**
 * @author Mayur Ahir (mayur@mayurahir.com)
 * @created Fri May 2, 2014
 */

class page_installs extends Page {

    function init() {
        parent::init();
    }

    function page_index() {
        $cr = $this->add('CRUD');
        $m = $cr->setModel('Model_ATKApi_Installs');
        // $vv = $this->add('Model_ATKApi_Installs');
        // var_dump($vv->getRows());
    }

}