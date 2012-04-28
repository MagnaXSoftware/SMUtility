<?php
/**
 * SMUtility
 *
 * @package Core
 * @subpackage JSON
 */

/**
 * Shorthand for DIRECTORY_SEPARATOR
 * @package Core
 * @subpackage JSON
 */
define('DS', DIRECTORY_SEPARATOR);
/**
 * Location of the root of the script
 * @package Core
 * @subpackage JSON
 */
define('ROOT', '.' . DS);

require_once ROOT . 'display.php';
require_once ROOT . 'functions.php';

/* first check for info command. If found, load meta from script */
if (isset($_GET['info']) && isset($_GET['script']) && !empty($_GET['script'])) {
    if ($_GET['script'] == 'core') {
        $meta = $systemMeta;
        $context = false;
    } else {
        try {
            $meta = Load::meta($_GET['script']);
        } catch (Exception $e) {
            JSON::display('Error', $e->getMessage());
            exit();
        }
        $context = true;
    }
    ksort($meta);
    JSON::display($meta['name'] . ' Info', $meta);
    exit();
}

/* second check if a plugin has been specified, if so, load it and run the current view */
if (isset($_GET['script']) && !empty($_GET['script'])) {
    try {
        $obj = Load::plugin($_GET['script']);
        $meta = Load::meta($_GET['script']);
    } catch (Exception $e) {
        JSON::display('Error', $e->getMessage());
        exit();
    }
    if (isset($_GET['do'])) {
        $pluginValues = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, $meta['ID']) !== false) {
                $pluginValues[str_replace($meta['ID'] . '_', "", $key)] = $value;
            }
        }
        try {
            $content = JSON::generateResult($obj, $pluginValues);
        } catch (Exception $e) {
            JSON::display('Error', $e->getMessage());
            exit();
        }
    } else {
        $content = JSON::generateForm($obj, $meta['ID']);
    }
    JSON::display($meta['name'], $content);
    exit();
}

/* nothing matched so display list of plugins */
$content = array();
foreach (scandir(ROOT . 'plugins') as $item) {
    try {
        $meta = Load::meta($item);
        $content[] = $meta;
    } catch (Exception $e) {
        // An exception signifies that the directory is not a plugin.
        // We skip that directory
    }
}

JSON::display('Script List', $content);
