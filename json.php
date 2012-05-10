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

/**
 * To make sure irrecoverable headers send the right header. It is reverted in 
 * JSON::display() 
 */
header('HTTP/1.1 500 Internal Server Error');

/**
 * JSON-ifies recoverable errors
 * 
 * @param int $code
 * @param string $string
 * @param string $file
 * @param int $line
 * @param array $context 
 */
function my_error_handler($code, $string, $file, $line, $context) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-type: application/json; charset=utf-8');
    
    echo json_encode(array(
        'code'      => $code,
        'string'    => $string,
        'file'      => $file,
        'line'      => $line,
    ));
    exit;
}
xdebug_disable();
set_error_handler('my_error_handler', E_ALL);

require_once ROOT . 'display.php';
require_once ROOT . 'functions.php';

/* first check for info command. If found, load meta from script */
if (isset($_GET['info']) && isset($_GET['script']) && !empty($_GET['script'])) {
    if ($_GET['script'] == 'core') {
        $meta = Load::systemMeta();
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
    JSON::display(null, $meta);
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
    JSON::display(null, array('ID' => $meta['ID'], 'name' => $meta['name'], 'form' => $content));
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

JSON::display(null, $content);
