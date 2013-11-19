<?php
// create folder pub
// create folder pub/atk4
// create symlink pub/atk4/templates to "../atk4/templates"
// create file pub/index.php with:

// include '../atk4/loader.php';
include '../vendor/autoload.php';
include '../lib/Frontend.php';

class RootedFrontend extends Frontend{

    function addSharedLocations(){
        $parent_directory=dirname(dirname(@$_SERVER['SCRIPT_FILENAME']));

        // Private location contains templates and php files YOU develop yourself
        $this->private_location = $this->pathfinder->addLocation(/*'my-private',*/array(
            'docs'=>'docs',
            'php'=>'lib',
            'page'=>'page',
            'addons'=>array('atk4-addons'/*,'vendor'*/),
            'template'=>'templates',
        ))->setBasePath($parent_directory)
        ;

        // this public location cotains YOUR js, css and images, but not templates
        $this->public_location = $this->pathfinder->addLocation(array(
            'js'=>'js',
            'css'=>'css',
            'public'=>'.',  // use with < ?public? > tag in your template
        ))->setBasePath($parent_directory.'/public')
        ;

        // agile toolkit relies on some images
        $this->public_atk4 = $this->pathfinder->addLocation(array(
            'js'=>'js',
            'css'=>'css',
            'public'=>'.',    // use with new < ?public? > tag in your template
//            'template'=>'.',  // backward compatibility with < ?template? > for images
        ))->setBasePath($parent_directory.'/public/atk4/atk4')
        ;
    }
    /**
     * Extends automated parsing of tags inside all views of Agile Toolkit
     * to also recognize < ?public? > tag (without spaces)
     */
    function setTags($t){
        $t->eachTag('public',array($this,'_locatePublic'));
        return parent::setTags($t);
    }
    function _locateTemplate($path){
        return $this->locateURL('public',$path);
    }

    function getConfig($path, $default_value = undefined){
        if(is_null($this->config)){
            $this->readConfig('../config-default.php');
            $this->readConfig('../config.php');
        }
        return parent::getConfig($path,$default_value);
    }




    function init(){
        $this->public_location->setBaseURL($this->api->url('/'));
        $this->public_atk4->setBaseURL($this->api->url('/').'/atk4/atk4');
        parent::init();
    }


}
$api = new RootedFrontend('myrealm');
$api->main();
