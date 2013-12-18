<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 11/20/13
 * Time: 11:43 AM
 * To change this template use File | Settings | File Templates.
 */
namespace installer;
use \Composer\Script\Event;
class InstallApi {

    private static $api_path        = 'app/';
    private static $api_public_path = 'public/';

    static $packages = array();
    static $apis     = array();

    public static function postPackageInstall(Event $event) {
        $installedPackage = $event->getOperation()->getPackage();
        self::memorizePackage($installedPackage,$event);
    }
    public static function postInstallCmd(Event $event) {
        self::getApiList();
        self::createSymLinks($event);
        self::saveAddonsJSON(self::$packages,$event);
    }



    /*
        array(7) {
          ["name"] =>  "rvadym/languages"
          ["path"] =>  "rvadym/languages"
          ["dirs"] =>  {
            [0] => "rvadym"
            [1] => "languages"
          }
          ["addon_full_path"]       => "vendor/rvadym/languages"
          ["addon_symlink_goes_to"] => "../../../vendor/rvadym/languages/public"
          ["addon_public_symlink"]  => "public/rvadym_languages"
          ["addon_public_path"]     => "vendor/rvadym/languages/public"
        }
    */
    private static function memorizePackage($installedPackage,Event $event) {
        $name                  = $installedPackage->getPrettyName();
        $dirs                  = explode('/',$name);
        $addon_full_path       = 'vendor/' . $name;
        $addon_symlink_goes_to = '../../../vendor/' . $name . '/public';
        if ($name == 'atk4/atk4') {
            $addon_symlink_goes_to .= '/atk4';
        }
        $addon_public_path     = $addon_full_path . (($name == 'atk4/atk4')? '/public/atk4' : '/public');
        $addon_symlink_name    =                    (($name == 'atk4/atk4')? 'atk4'         : implode('_',$dirs));
        $addon_public_symlink  = self::$api_public_path . $addon_symlink_name;

        self::$packages[] = array(
            'name'                  => $name,
            'path'                  => $name,
            'dirs'                  => $dirs,
            'addon_full_path'       => $addon_full_path,
            'addon_symlink_goes_to' => $addon_symlink_goes_to,
            'addon_symlink_name'    => $addon_symlink_name,
            'addon_public_symlink'  => $addon_public_symlink,
            'addon_public_path'     => $addon_public_path,
        );
    }


    private static function getApiList() {
        $apis = scandir(self::$api_path); array_shift($apis); array_shift($apis);
        self::$apis = $apis;
    }
    private static function createSymLinks(Event $event) {
        foreach (self::$apis as $api) {
            self::createSymLinksForAPI($event, $api);
        }
    }
    private static function createSymLinksForAPI(Event $event, $api) {

        $api_path        = self::$api_path . $api . '/';         // api/admin/
        $api_public_path = $api_path . self::$api_public_path;   // api/admin/public/

        if (!self::fileExist($api_public_path)) {
            return false;                                        //there is no public dir for this api
        }

        foreach (self::$packages as $package) {
            if (self::fileExist($package['addon_public_path'])) {                      // vendor/rvadym/languages/public
                $event->getIO()->write('   -> '.$package['path']);
                $event->getIO()->write('      Api path        = '.$api_path);
                $event->getIO()->write('      Api public path = '.$api_public_path);
                $event->getIO()->write("      Addon public exist.");
                $installed = false;
                $addon_sym_link = $api_path.$package['addon_public_symlink'];          // api/admin/  .  public/rvadym_languages
                $event->getIO()->write('      Api addon sym link path = '.$addon_sym_link);
                if (self::fileExist($addon_sym_link)) {                                // api/admin/public/rvadym_languages
                    //$do_delete = $event->getIO()->ask("      File ".$addon_sym_link." already exist. Do you want to rewrite it?(Y/n) ");
                    $do_delete = 'Y';
                    if ($do_delete == 'Y') {
                        if(is_link($addon_sym_link)) {
                            unlink($addon_sym_link);
                        } else {
                            exit($addon_sym_link." exists but not symbolic link\n");
                        }
                        $installed = false;
                        $event->getIO()->write("      Symlink successfully deleted");
                    } else {
                        $installed = true;
                    }
                }
                if (!$installed) {
                    $event->getIO()->write("      Creating symlink");
                    $event->getIO()->write('      ln -s '.$package['addon_symlink_goes_to'].' '. $addon_sym_link);
                    symlink($package['addon_symlink_goes_to'],$addon_sym_link);
                    $event->getIO()->write("      Addon public symlink successfully created\n");
                } else {
                    $event->getIO()->write("      Symlink ".$addon_sym_link." was not changed.\n");
                }
            } else {
                $event->getIO()->write("      There is no public dir for addon ".$package['name'].". ".$package['addon_public_path'].".\n");
            }
        }
    }

    private static function saveAddonsJSON($arr,Event $event) {
        $filename = 'sandbox_addons.json';
        $save_json = $arr;
        if (self::fileExist($filename)) {
            // read and merge
            $json = file_get_contents($filename);
            $objects = json_decode($json,true);
            foreach ($arr as $pk => $plugin) {
                foreach ($objects as $ok => $obj) {
                    if ($obj['name'] == $plugin['name']) {
                        $objects[$ok] = $plugin;
                        unset($arr[$pk]);
                    }
                }
            }
            foreach ($arr as $pk => $plugin) {
                $objects[] = $plugin;
            }
            // double check if addon exist
            $event->getIO()->write("     *** Check if some addons was deleted.");
            foreach ($objects as $ok => $obj) {
                if (self::fileExist($obj['addon_full_path'])) {
                    $event->getIO()->write("      +++ Addon ".$obj['addon_full_path']." exist.");
                } else {
                    $event->getIO()->write("      --- Addon ".$obj['addon_full_path']." NOT exist.");
                    unset($objects[$ok]);
                }
            }
            $save_json = $objects;
        }
        file_put_contents($filename,self::indent(json_encode($save_json)));
        $event->getIO()->write("      $filename created");
    }

    private static function fileExist($path) {
        // TODO does not work if symlink is broken
        return ((file_exists($path) == 1)?true:false);
    }

    /**
     * Indents a flat JSON string to make it more human-readable.
     *
     * @param string $json The original JSON string to process.
     *
     * @return string Indented version of the original JSON string.
     */
    static function indent($json) {

        $result      = '';
        $pos         = 0;
        $strLen      = strlen($json);
        $indentStr   = '  ';
        $newLine     = "\n";
        $prevChar    = '';
        $outOfQuotes = true;

        for ($i=0; $i<=$strLen; $i++) {

            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;

            // If this character is the end of an element,
            // output a new line and indent the next line.
            } else if(($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos --;
                for ($j=0; $j<$pos; $j++) {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            $prevChar = $char;
        }

        return $result;
    }
}