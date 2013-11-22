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

    // conf
//    private static $
    private static $project_public_path = 'public/';

    static $count = 0;
    static $packages = array();

    public static function postPackageInstall(Event $event) {
        $installedPackage = $event->getOperation()->getPackage();
        self::memorizePackage($installedPackage);
    }
    public static function postInstallCmd(Event $event) {
        foreach (self::$packages as $package) {
            if (self::fileExist($package['addon_public_path'])) {
                $event->getIO()->write('   -> '.$package['path']);
                $event->getIO()->write("      Addon public exist.");
                $installed = false;
                if (self::fileExist($package['addon_public_symlink'])) {
                    $do_delete = $event->getIO()->ask("      File ".$package['addon_public_symlink']." already exist. Do you want to delete it?(Y/n) ");
                    if ($do_delete == 'Y') {
                        if(is_link($package['addon_public_symlink'])) {
                            unlink($package['addon_public_symlink']);
                        } else {
                            exit($package['addon_public_symlink']." exists but not symbolic link\n");
                        }
                        $installed = false;
                        $event->getIO()->write("      Symlink successfully deleted");
                    } else {
                        $installed = true;
                    }
                }
                if (!$installed) {
                    $event->getIO()->write("      Creating symlink");
                    $event->getIO()->write('      ln -s ../'.$package['addon_public_path'].' '. $package['addon_public_symlink']);
                    symlink('../'.$package['addon_public_path'],$package['addon_public_symlink']);
                    $event->getIO()->write("      Addon public symlink successfully created\n");
                } else {
                    $event->getIO()->write("      Symlink ".$package['addon_public_symlink']." was not changed.\n");
                }
            } else {
                $event->getIO()->write("      There is no public dir for addon ".$package['name'].". ".$package['addon_public_path'].".\n");
            }
        }
    }


    private static function memorizePackage($installedPackage) {
        $name               = $installedPackage->getPrettyName();
        $dirs               = explode('/',$name);
        $addon_full_path    = 'vendor/'.$name;
        $addon_symlink_path = '../vendor/'.$name;

        if ($name == 'atk4/atk4') {
            $addon_public_path  = $addon_full_path . '/public/atk4';
        } else {
            $addon_public_path  = $addon_full_path . '/public';
        }
        if ($name == 'atk4/atk4') {
            $addon_public_symlink = self::$project_public_path.'atk4';
        } else {
            $addon_public_symlink = self::$project_public_path.implode('_',$dirs);
        }

        self::$packages[] = array(
            'path'                 => $name,
            'dirs'                 => $dirs,
            'addon_full_path'      => $addon_full_path,
            'addon_symlink_path'   => $addon_symlink_path,
            'addon_public_symlink' => $addon_public_symlink,
            'addon_public_path'    => $addon_public_path,
        );
    }
    private static function fileExist($path) {
        return ((file_exists($path) == 1)?true:false);
    }
}