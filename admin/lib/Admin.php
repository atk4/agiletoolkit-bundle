<?php
class Admin extends Api_Admin {

    function init() {
        parent::init();

        // For improved compatibility with Older Toolkit. See Documentation.
        // $this->add('Controller_Compat42')
        //     ->useOldTemplateTags()
        //     ->useOldStyle()
        //     ->useSMLite();

        $this->api->menu->addMenuItem('','home');
    }
}
