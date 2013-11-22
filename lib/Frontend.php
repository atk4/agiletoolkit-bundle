<?php
class Frontend extends ApiFrontend {
    function init() {
        parent::init();
        $this->initAddons();


//        $publicDir = dirname(@$_SERVER['SCRIPT_FILENAME']);
//        $baseDir   = dirname($publicDir);
//
//
//        //$parent_directory=/*dirname(*/dirname(@$_SERVER['SCRIPT_FILENAME'])/*)*/;
//        var_dump($baseDir);
//
//        $this->pathfinder->public_location->addRelativeLocation('public/',
//            array(
//                'css'=>'css',
//                'public'=>'.',
//            )
//        );
//
//        $this->api->pathfinder->base_location->defineContents(array(
//            'docs'=>array('docs','doc'),  // Documentation (external)
//            'content'=>'content',          // Content in MD format
//            'addons'=>'vendor',
//            'php'=>array('shared',),
//        ));



        $this->add('jUI');
    }

    function initAddons() {

    }

    function initLayout(){

        $l = $this->add('Layout_Fluid');

//        $m = $l->addMenu('MainMenu');
//        $m->addClass('atk-wrapper');
//        $m->addMenuItem('index','Home');
//        $m->addMenuItem('services','Services');
//        $m->addMenuItem('team','Team');
//        $m->addMenuItem('portfolio','Portfolio');
//        $m->addMenuItem('contact','Contact');
//
//        $l->addFooter()->addClass('atk-swatch-seaweed atk-section-small')->setHTML('
//            <div class="row atk-wrapper">
//                <div class="col span_4">
//                    © 1998 - 2013 Agile55 Limited
//                </div>
//                <div class="col span_4 atk-align-center">
//                    <img src="'.$this->pm->base_path.'images/powered_by_agile.png" alt="powered_by_agile">
//                </div>
//                <div class="col span_4 atk-align-right">
//                    <a href="http://colubris.agiletech.ie/">
//                        <span class="icon-key-1"></span> Client Login
//                    </a>
//                </div>
//            </div>
//        ');

        parent::initLayout();
    }

    // translations
    public $languages = false;
    function _($string) {
        // do not translate if only spases
        if (trim($string) == '') return $string;

        if (!$this->languages) {
            $this->add('\rvadym\languages\Controller_SessionLanguageSwitcher',array(
                'languages'=>array('en','ru'),
                'default_language'=>'en',
                'translation_dir_path'=>$this->api->pm->base_directory.'../translations',
            ));
            //$this->x_ls->setModel('Translations');
        }
        return $this->language_switcher->__($string);
    }
}
