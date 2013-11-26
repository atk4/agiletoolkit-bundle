<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 11/25/13
 * Time: 7:25 PM
 * To change this template use File | Settings | File Templates.
 */

namespace installer;
class Controller_Addon extends AbstractController {

    public $atk_version='4.3';

    public $namespace=null;

    public $base_path=null;

    public $has_assets=false;

    function init() {
        parent::init();
        $this->api->requires('atk',$this->atk_version);
        $this->namespace = substr(get_class($this), 0, strrpos(get_class($this), '\\'));
        $this->base_path=$this->api->locatePath('addons',$this->namespace);
    }

    /**
     * This routes certain prefixes to an add-on. Call this method explicitly
     * from init() if necessary.
     */
    function routePages($page_prefix) {
        if ($this->api instanceof api/Frontend) {
            $this->api->routePages($page_prefix, $this->namespace);
        }
    }

    /**
     * This defines the location data for the add-on. Call this method
     * explicitly from init() if necessary.
     */
    function addLocation($contents,$public_contents=null) {
        $this->location = $this->api->pathfinder->addLocation($contents);
        $this->location->setBasePath($this->base_path);


        // If class has assets, those have probably been installed
        // into the public location
        if ($this->has_assets) {
            $this->location = $this->api->pathfinder->addLocation($contents);
            $this->location->setBasePath($this->base_path);

            if(is_null($public_contents)) {
                $public_location=array(
                    'public'=>'.',
                    'js'=>'js',
                    'css'=>'css',
                );
            }

            $this->public_location=$this->api->pathfinder->public_location
                ->addRelativeLocation($namespace.'/public',$public_contents);
        }

        return $this->location;
    }

    /**
     * This method will rely on location data to link
     */
    function installAssets() {

        // Creates symlink inside /public/my-addon/ to /vendor/my/addon/public

        // TODO: if $this->namespace contains slash, then created
        // this folder under $api->pathfinder->public_location
        //
        // TODO: create a symlink such as $this->namespace pointing
        // to
        //
        // TODO: if this already exist, don't mess it up. Also, resolve
    }

    /**
     * Addon may requrie user to have license for ATK or some other
     * piece of software to function properly. This is will be called
     * during installation and then later on ocassionally, but not
     * on production environment.
     *
     * Agile Toolkit provides universal way for checking licenses. If
     * you are building commercial product with Agile Toolkit, then
     * you need to use unique identifier for $software. Provide the name
     * of your public key certificate and also supply md5sum of that
     * certificate as last parameter, to make sure developer wouldn't
     * simply substitute public key with another one.
     *
     * Public key must be bundled along with the release of your software
     *
     * This method will return true / false. If you have multiple public
     * keys (expired ones), you can call this method several times.
     *
     * The information about the private key specifically issued to the
     * user will be stored in configuration file.
     */
    function licenseCheck($type,$software='atk',$pubkey=null,$pubkey_md5=null) {
        // TODO: move stuff here from ApiWeb -> licenseCheck
        //
        // TODO: we might need to hardcode hey signature or MD
    }

    function installDatabase() {
        // TODO: If add-on comes with some database requirement, then this
        // method should execute the migrations which will install and/or
        // upgrade the database.
    }

    function checkConfiguration() {
        // Addon may requrie user to add some stuff into configuration file.
        //
        // This method must return 'true' or 'false' if some configuration
        // options are missing.
        //
        // This method must not complain about optional arguments. If you are
        // introducing a new configuration options in a new version of your
        // add-on, then you must always provide reasonable defaults.
        //
        // This method can still return false, while your defaults should
        // prevent application from crashing.
        //
        // Admin will redirect user to your add-on configuration page
        // if admin is logging in or at least provide some useful
        // information.
    }

}