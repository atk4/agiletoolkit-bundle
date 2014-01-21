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

    function addAddonsLocations() {
        return;
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
