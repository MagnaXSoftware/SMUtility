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
/**
 * Location of the assets directory.
 * @package Core
 * @subpackage Display 
 */
define('ASSET_DIR', 'assets/');

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
        HTML::display('Error', HTML::grid(HTML::box($e->getMessage())));
        exit();
    }
    /*
     * If info variable is set, display metadata and exit.
     */
    if (isset($_GET['info'])) {
        ksort($meta);
        $content = "";
        foreach ($meta as $key => $value) {
            $content .= "<dt>{$key}</dt><dd>{$value}</dd>";
        }
        if ($_GET['script'] != 'core') {
            HTML::display($meta['name'] . ' Info', HTML::grid(HTML::box(HTML::wrap('dl', $content))), $meta);
        } else {
            HTML::display($meta['name'] . ' Info', HTML::grid(HTML::box(HTML::wrap('dl', $content))));
        }
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
            HTML::display($meta['name'], HTML::generateResult($obj, $pluginValues), $meta);
            exit();
        } catch (Exception $e) {
            HTML::display('Error', HTML::grid(HTML::box($e->getMessage())), $meta);
            exit();
        }
    }
    /*
     * Nothing else matched, so display configuration options and exit.
     */
    HTML::display($meta['ID'], HTML::generateForm($obj, $meta['ID']), $meta);
    exit();
}

/*
 * No script variable present, so display the list of plugins.
 */
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
