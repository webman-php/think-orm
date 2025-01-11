<?php
namespace Webman\ThinkOrm;

class Install
{
    const WEBMAN_PLUGIN = true;

    /**
     * @var array
     */
    protected static array $pathRelation = [];

    /**
     * Install
     * @return void
     */
    public static function install(): void
    {
        $thinkorm_file = config_path() . '/think-orm.php';
        if (!is_file($thinkorm_file)) {
            echo 'Create config/think-orm.php' . PHP_EOL;
            copy(__DIR__ . '/config/think-orm.php', $thinkorm_file);
        }
        static::installByRelation();
    }

    /**
     * Uninstall
     * @return void
     */
    public static function uninstall(): void
    {
        $thinkorm_file = config_path() . '/think-orm.php';
        if (is_file($thinkorm_file)) {
            echo 'Remove config/think-orm.php' . PHP_EOL;
            unlink($thinkorm_file);
        }
        self::uninstallByRelation();
    }

    /**
     * installByRelation
     * @return void
     */
    public static function installByRelation(): void
    {
        foreach (static::$pathRelation as $source => $dest) {
            if ($pos = strrpos($dest, '/')) {
                $parent_dir = base_path().'/'.substr($dest, 0, $pos);
                if (!is_dir($parent_dir)) {
                    mkdir($parent_dir, 0777, true);
                }
            }
            copy_dir(__DIR__ . "/$source", base_path()."/$dest");
        }
    }

    /**
     * uninstallByRelation
     * @return void
     */
    public static function uninstallByRelation(): void
    {
        foreach (static::$pathRelation as $source => $dest) {
            $path = base_path()."/$dest";
            if (!is_dir($path) && !is_file($path)) {
                continue;
            }
            /*if (is_link($path) {
                unlink($path);
            }*/
            remove_dir($path);
        }
    }
    
}