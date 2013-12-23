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

        $this->addProjectLocations();
        $this->addAddonsLocations();
        $this->add('jUI');
        $this->initAddons();

        $this->db = $this->add('DB');
        $this->db->connect();

        $this->api->menu->addMenuItem('','home');

        $this->add('sandbox/Initiator');
        $this->p = $this->add('Controller_Police');

        try {
          $this->add('rvadym/blog/Initiator');
        } catch (Exception $e){
        }
    }


    function addProjectLocations() {
        $this->pathfinder->addLocation(
            array(
                'page'=>'page',
                'php'=>'../shared',
                'addons'=>array('../addons'),
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
        $file = $base_path.'/../../sandbox_addons.json';
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
        $file = $base_path.'/sandbox_addons.json';
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
        if ($_GET['debug']) {
            $this->p->addDebugView($this->page_object);
        }
        try {
            $this->p->guard();
        } catch (Exception $e) {
            $this->p->addErrorView($this->page_object);
        }
    }
}
