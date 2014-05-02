<?php
/**
 * @author Mayur Ahir (mayur@mayurahir.com)
 * @created Fri May 2, 2014
 */

class page_addons extends Page {

    function init() {
        parent::init();
    }

    function page_index() {
        $cr = $this->add('CRUD', ['allow_edit' => false]);
        $m = $cr->setModel('Model_ATKApi_Addons');
    }

}
