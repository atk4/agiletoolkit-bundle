<?php
class Admin extends Api_Admin {
    public $app_public_path;
    public $app_base_path;
    public $addons;
    function init() {
        parent::init();
        $this->app_public_path = dirname(@$_SERVER['SCRIPT_FILENAME']);
        $this->project_base_url = (dirname(dirname($this->pm->base_path)));
        $this->app_base_path = dirname($this->app_public_path);
        $this->project_base_path = dirname($this->app_base_path);

//        $this->add('Controller_Compat42')/*->useOldTemplateTags()*/->useOldStyle()->useSMLite();

        $this->addLocations();
        $this->addProjectLocations();
        $this->addAddonsLocations();
        $this->add('jUI');
        $this->initAddons();


        $this->api->menu->addMenuItem('','home');

        //$this->add('ide/Initiator');
    }

    function addLocations() {
        $this->api->pathfinder->base_location->defineContents(array(
            'docs'   =>array('docs','doc'),   // Documentation (external)
            'content'=>'content',             // Content in MD format
            'addons' =>array('vendor',),
            'page'   =>array('vendor',),
            'php'    =>array('shared',),
        ));//->setBasePath($this->app_base_path);
        $this->api->pathfinder->base_location->defineContents(array(
            'addons' =>array('/atk4-ide.phar/addons'),
            'page'   =>array('/atk4-ide.phar/addons/ide/page'),
        ))->setBasePath('phar:');

        $this->api->pathfinder->atk_public
          ->setBasePath($this->project_base_path.'/vendor/atk4/atk4/public/atk4')
          ->setBaseURL($this->project_base_url.'/vendor/atk4/atk4/public/atk4')
          ;
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
