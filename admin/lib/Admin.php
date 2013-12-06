<?php
class Backend extends Api_Admin {
    public $app_public_path;
    public $app_base_path;
    public $addons;
    function init() {
        parent::init();
        $this->app_public_path = dirname(@$_SERVER['SCRIPT_FILENAME']);
        $this->app_base_path = dirname(dirname(@$_SERVER['SCRIPT_FILENAME']));

//        $this->add('Controller_Compat42')/*->useOldTemplateTags()*/->useOldStyle()->useSMLite();

        $this->addLocations();
        $this->addProjectLocations();
        $this->addAddonsLocations();
        $this->add('jUI');
        $this->initAddons();


        $this->api->menu->addMenuItem('','home');

        $this->add('ide/Initiator');
    }

    function addLocations() {
        $this->api->pathfinder->base_location->defineContents(array(
            'docs'   =>array('docs','doc'),   // Documentation (external)
            'content'=>'content',          // Content in MD format
            'addons' =>array('vendor','../../atk4-ide/addons'),
            'page'   =>array('vendor','../../atk4-ide/addons/ide/page'),
            'php'    =>array('shared',),
        ));//->setBasePath($this->app_base_path);
    }

    function addProjectLocations() {
//        $this->pathfinder->base_location->setBasePath($this->app_base_path);
//        $this->pathfinder->base_location->setBaseUrl($this->url('/'));
        $this->pathfinder->addLocation(
            array(
                'page'=>'page',
                'php'=>'../shared',
            )
        )->setBasePath($this->app_base_path);
        $this->pathfinder->addLocation(
            array(
                'js'=>'js',
                'css'=>'css',
            )
        )
                ->setBaseUrl($this->url('/'))
                ->setBasePath($this->app_public_path)
        ;
    }

    function addAddonsLocations() {
        $base_path = $this->pathfinder->base_location->getPath();
        $file = $base_path.'/../../atk4_addons.json';
        if (file_exists($file)) {
            $json = file_get_contents($file);
            $objects = $this->addons = json_decode($json);
            foreach ($objects as $obj) {
                // Private location contains templates and php files YOU develop yourself
                /*$this->private_location = */
                $this->api->pathfinder->addLocation(array(
                    'docs'      => 'docs',
                    'php'       => 'lib',
                    'page'      => 'page',
                    'template'  => 'templates',
                ))
                        ->setBasePath($base_path.'/'.$obj->addon_full_path)
                ;

                $addon_public = $obj->addon_symlink_name;
                // this public location cotains YOUR js, css and images, but not templates
                /*$this->public_location = */
                $this->api->pathfinder->addLocation(array(
                    'js'     => 'js',
                    'css'    => 'css',
                    'public' => './',
                    //'public'=>'.',  // use with < ?public? > tag in your template
                ))
                        ->setBasePath($this->app_base_path.'/'.$obj->addon_public_symlink)
                        ->setBaseURL($this->api->url('/').$addon_public) // $this->api->pm->base_path
                ;
            }
        }
    }
    function initAddons() {
        $base_path = $this->pathfinder->base_location->getPath();
        $file = $base_path.'/atk4_addons.json';
        if (file_exists($file)) {
            $json = file_get_contents($file);
            $objects = json_decode($json);
            foreach ($objects as $obj) {
                // init addon
                $init_class_path = $base_path.'/'.$obj->addon_full_path.'/lib/Initiator.php';
                if (file_exists($init_class_path)) {
                    $class_name = str_replace('/','\\',$obj->name.'\\Initiator');
                    $init = $this->add($class_name,array(
                        'addon_obj' => $obj,
                    ));
                }
            }
        }
    }

    function initLayout(){
        parent::initLayout();
    }
}
