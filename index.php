<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '.' . DS);

require_once ROOT . 'display.php';
require_once ROOT . 'functions.php';

/* clean input. only allow alphanumerics */
$clean_GET = array();
foreach ($_GET as $key => $value) {
	$clean_GET[preg_replace("/[^a-zA-Z0-9]/", "", $key)] = preg_replace("/[^a-zA-Z0-9]/", "", $value);
}
unset($_GET);
$_GET = $clean_GET;

$systemMeta = array(
	'ID'		=> 'core',
	'name'		=> 'SMUtility',
	'version'	=> 'dev',
	'author'	=> 'AfroSoft'
);

/* first check for info command. If found, load meta from script */

if (isset($_GET['info']) && isset($_GET['script']) && !empty($_GET['script'])) {
	if ($_GET['script'] == 'core') {
		$meta = $systemMeta;
	} else {
		try {
		$meta = loadMeta($_GET['script']);
		} catch (Exception $e) {
			display('Error', HTML::grid(HTML::box($e->getMessage())));
			exit();
		}
	}
	ksort($meta);
	$content = "<dl>";
	foreach ($meta as $key => $value) {
		$content .= "<dt>{$key}</dt><dd>{$value}</dd>";
	}
	$content .= "</dl>";
	display($meta['name'] . ' Info', HTML::grid(HTML::box($content)), $meta);
	exit();
}


/* second check if a plugin has been specified, if so, load it and run the current view */

if (isset($_GET['script']) && !empty($_GET['script'])) {
	try {
		$obj = loadPlugin($_GET['script']);
		$meta = loadMeta($_GET['script']);
	} catch (Exception $e) {
		display('Error', HTML::grid(HTML::box($e->getMessage())));
		exit();
	}
	if (isset($_GET['do'])) {
		$pluginValues = array();
		foreach ($_POST as $key => $value) {
			if (strpos($key, $_GET['script']) !== false) {
				$pluginValues[str_replace($_GET['script'] . '_', "", $key)] = $value;
			}
		}
		var_dump($_POST, $pluginValues);
		try {
			$content = generateResult($obj->execute($pluginValues));
		} catch (Exception $e) {
			display('Error', HTML::grid(HTML::box($e->getMessage())));
			exit();
		}
	} else {
		$content = generateForm($obj->form(), $meta['ID']);
	}
	display($meta['name'], $content, $meta);
	exit();
}


/* as a failsafe load each plugin one by one and display them as a list. */

$content = "<ul>";
foreach (scandir(ROOT . 'plugins') as $item) {
	try {
		$meta = loadMeta($item);
		$content .= '<li><a href="?script=' . $meta['ID'] . '">' . $meta['name'] . '</a> - <a href="?info&amp;script=' . $meta['ID'] . '">Info</a></li>';
	} catch (Exception $e) {}
}
$content .= "</ul>";

display('Script List', HTML::grid(HTML::box($content)));
var_dump(scandir(ROOT . 'plugins'));
