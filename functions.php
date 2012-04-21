<?php
/**
 * Generic helper functions
 *
 * @package Core
 * @subpackage Helpers
 */

/**
 * All loading related function, implemented as static methods of this class.
 *
 * @package Core
 * @subpackage Helpers
 */
class Load {

    /**
     * Loads a plugin and returns the instanciated object
     *
     * @param string $name
     * @return object
     * @throws Exception
     */
    public static function plugin($name) {
        $file = ROOT . 'plugins' . DS . $name . DS . 'plugin.php';
        if (!file_exists($file)) {
            throw new Exception("The specified plugin '{$name}' hasn't been installed in this system.");
            exit();
        }
        require_once $file;
        $class = 'SMU_' . ucfirst($name);
        return new $class;
    }

    /**
     * Loads the metadata for a plugin and returns it as an array
     *
     * @param string $name
     * @return array
     * @throws Exception
     */
    public static function meta($name) {
        $file = ROOT . 'plugins' . DS . $name . DS . 'plugin.meta';
        if (!file_exists($file)) {
            throw new Exception("The specified plugin '{$name}' doesn't have any metadata.");
        }
        return parse_ini_file($file);
    }
}
