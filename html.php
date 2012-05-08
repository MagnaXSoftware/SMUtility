<?php
/**
 * SMUtility
 *
 * @package Core
 */

/**
 * Shorthand for DIRECTORY_SEPARATOR
 * @package Core
 */
define('DS', DIRECTORY_SEPARATOR);
/**
 * Location of the root of the script
 * @package Core
 */
define('ROOT', '.' . DS);

require_once ROOT . 'display.php';
require_once ROOT . 'functions.php';

$systemMeta = array(
    'ID' => 'core',
    'name' => 'SMUtility',
    'version' => '1.0',
    'author' => 'AfroSoft'
);

/* first check for info command. If found, load meta from script */
if (isset($_GET['info']) && isset($_GET['script']) && !empty($_GET['script'])) {
    if ($_GET['script'] == 'core') {
        $meta = $systemMeta;
        $context = false;
    } else {
        try {
            $meta = Load::meta($_GET['script']);
        } catch (Exception $e) {
            HTML::display('Error', HTML::grid(HTML::box($e->getMessage())));
            exit();
        }
        $context = true;
    }
    ksort($meta);
    $content = "";
    foreach ($meta as $key => $value) {
        $content .= "<dt>{$key}</dt><dd>{$value}</dd>";
    }
    if ($context == true) {
        HTML::display($meta['name'] . ' Info', HTML::grid(HTML::box(HTML::wrap('dl', $content))), $meta);
    } else {
        HTML::display($meta['name'] . ' Info', HTML::grid(HTML::box(HTML::wrap('dl', $content))));
    }
    exit();
}

/* second check if a plugin has been specified, if so, load it and run the current view */
if (isset($_GET['script']) && !empty($_GET['script'])) {
    try {
        $obj = Load::plugin($_GET['script']);
        $meta = Load::meta($_GET['script']);
    } catch (Exception $e) {
        HTML::display('Error', HTML::grid(HTML::box($e->getMessage())));
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
            $content = HTML::generateResult($obj, $pluginValues);
        } catch (Exception $e) {
            HTML::display('Error', HTML::grid(HTML::box($e->getMessage())), $meta);
            exit();
        }
    } else {
        $content = HTML::generateForm($obj, $meta['ID']);
    }
    HTML::display($meta['name'], $content, $meta);
    exit();
}

/* nothing matched so display index */
$content = "";
foreach (scandir(ROOT . 'plugins') as $item) {
    try {
        $meta = Load::meta($item);
        $content .= '<li><a href="?script=' . $meta['ID'] . '">' . $meta['name'] . '</a> - <a href="?info&amp;script=' . $meta['ID'] . '">Info</a></li>';
    } catch (Exception $e) {
        // An exception signifies that the directory is not a plugin.
        // We skip that directory
    }
}

HTML::display('Script List', HTML::grid(HTML::box(HTML::wrap('ul', $content))));
