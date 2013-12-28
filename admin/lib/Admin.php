<?php
class Admin extends Api_Admin {
    public $app_public_path;
    public $app_base_path;
    public $addons;
    function init() {
        parent::init();
        $this->db = $this->add('DB');
        $this->db->connect();

        $this->api->menu->addMenuItem('','home');
        $this->api->menu->addMenuItem('install','Install Addon');
    }


    function addProjectLocations() {

        $this->app_public_path = dirname(@$_SERVER['SCRIPT_FILENAME']);
        $this->project_base_url = (dirname(dirname($this->pm->base_path)));
        $this->app_base_path = dirname($this->app_public_path);
        $this->project_base_path = dirname($this->app_base_path);

        $this->pathfinder->addLocation(
            array(
                'page'=>'page',
                'php'=>'../shared',
                'addons'=>array('../vendor'),
            )
        )
                ->setBasePath($this->app_base_path)
        ;
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
    function initAddons() {
        $this->addAddonsLocations();
        $this->addProjectLocations();
        $base_path = $this->pathfinder->base_location->getPath();
        foreach ($this->getSndBoxAddonReader()->getReflections() as $addon) {
            // init addon
            $init_class_path = $base_path.'/../'.$addon->get('addon_full_path').'/lib/Initiator.php';
            if (file_exists($init_class_path)) {
                //var_dump($init_class_path.' exist'); echo '<hr>';
                $class_name = str_replace('/','\\',$addon->get('name').'\\Initiator');
                $init = $this->add($class_name,array(
                    'addon_obj' => $addon,
                ));
            } else {
                //var_dump($init_class_path.' NOT exist'); echo '<hr>';
            }
        }
    }

    // sandbox addons json reader
    private $sandbox_addon_reader = null;
    function getSndBoxAddonReader() {
        if (!$this->sandbox_addon_reader) {
            $this->sandbox_addon_reader = $this->add('sandbox/Controller_AddonsConfig_Reader');
        }
        return $this->sandbox_addon_reader;
    }
    function unsetSndBoxAddonReader() {
        $this->sandbox_addon_reader = null;
    }

    function initLayout(){
        parent::initLayout();
        if ($_GET['debug']) {
            $this->police->addDebugView($this->page_object);
        }
        try {
            $this->police->guard();
        } catch (Exception $e) {
            $this->police->addErrorView($this->page_object);
        }
    }

    function addAddonsLocations() {
        $base_path = $this->pathfinder->base_location->getPath();
        foreach ($this->getSndBoxAddonReader()->getReflections() as $addon) {
            // Private location contains templates and php files YOU develop yourself
            /*$this->private_location = */
            $this->api->pathfinder->addLocation(array(
                'docs'      => 'docs',
                'php'       => 'lib',
                'addons'    => '../..',
                'page'      => 'page',
                'template'  => 'templates',
            ))
                    ->setBasePath($base_path.'/../'.$addon->get('addon_full_path'))
            ;

            $addon_public = $addon->get('addon_symlink_name');
            // this public location cotains YOUR js, css and images, but not templates
            /*$this->public_location = */
            $this->api->pathfinder->addLocation(array(
                'js'     => 'js',
                'css'    => 'css',
                'public' => './',
                //'public'=>'.',  // use with < ?public? > tag in your template
            ))
                    ->setBasePath($base_path.$this->app_base_path.'/'.$addon->get('addon_public_symlink'))
                    ->setBaseURL($this->api->url('/').$addon_public) // $this->api->pm->base_path
            ;
        }
    }
}
