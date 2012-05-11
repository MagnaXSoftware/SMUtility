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
if (function_exists('xdebug_disable')) {
    xdebug_disable();
}
set_error_handler('my_error_handler', E_ALL);

require_once ROOT . 'display.php';
require_once ROOT . 'functions.php';

/* 
 * First, check for a script variable. 
 * If defined, load script & metadata associated.
 */
if (isset($_GET['script']) && !empty($_GET['script'])) {
    try {
        if ($_GET['script'] == 'core') {
            $meta = Load::systemMeta();
        } else {
            $obj = Load::plugin($_GET['script']);
            $meta = Load::meta($_GET['script']);
        }
    } catch (Exception $e) {
        JSON::display('Error', $e->getMessage());
        exit();
    }
    /*
     * If info variable is set, display metadata and exit.
     */
    if (isset($_GET['info'])) {
        ksort($meta);
        JSON::display(null, $meta);
        exit();
    }
    /*
     * If do variable is set, execute script, display metadata and exit.
     */
    if (isset($_GET['do'])) {
        $pluginValues = array();
        foreach ($_POST as $key => $value) {
            if (strpos($key, $meta['ID']) !== false) {
                $pluginValues[str_replace($meta['ID'] . '_', "", $key)] = $value;
            }
        }
        try {
            JSON::display(null, JSON::generateResult($obj, $pluginValues));
            exit();
        } catch (Exception $e) {
            JSON::display('Error', $e->getMessage());
            exit();
        }
    }
    /*
     * Nothing else matched, so display configuration options and exit.
     */
    JSON::display(null, JSON::generateForm($obj, $meta['ID']));
    exit();
}

/*
 * No script variable present, so display the list of plugins.
 */
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
