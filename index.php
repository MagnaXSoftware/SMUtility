<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '.' . DS);

require_once ROOT . 'display.php';
require_once ROOT . 'functions.php';

/* clean input. only allow alphanumerics */
foreach ($_GET as $key => $value) {
	$clean_GET[preg_replace("/[^a-zA-Z0-9]/", "", $key)] = preg_replace("/[^a-zA-Z0-9]/", "", $value);
}
unset($_GET);
$_GET = $clean_GET;

$systemMeta = array(
	'ID'		=> 'system',
	'name'		=> 'System',
	'version'	=> 'dev',
	'author'	=> 'AfroSoft'
);

/* first check for reserverd query string values, if so, do the action */

if (isset($_GET['info']) && isset($_GET['script']) && !empty($_GET['script'])) {
	if ($_GET['script'] == 'system') {
		$meta = $systemMeta;
	} else {
		$meta = loadPlugin($_GET['script'])->meta();
	}
	ksort($meta);
	$content = "<div class=\"grid_12\"><div class=\"box\"><dl>";
	foreach ($meta as $key => $value) {
		$content .= "<dt>{$key}</dt><dd>{$value}</dd>";
	}
	$content .= "</dl></div></div>";
	display($meta['name'] . ' Info', $content);
	exit();
}


/* second check if a plugin has been specified, if so, load it and run de current view */

if (isset($_GET['script']) && !empty($_GET['script'])) {
	$obj = loadPlugin($_GET['script']);
	$meta = $obj->meta();
	if (isset($_GET['do'])) {
		$pluginValues = array();
		foreach ($_POST as $key => $value) {
			if (strpos($key, $_GET['script']) !== false) {
				$pluginValues[str_replace($_GET['script'] . '_', "", $key)] = $value;
			}
		}
		$content = generateResult($obj->execute($pluginValues));
	} else {
		$content = generateForm($obj->form(), $meta['ID']);
	}
	display($meta['name'], $content, $obj);
	exit();
}


/* as a failsafe load each plugin one by one and display them as a list. */

$content = "<div class=\"grid_12\"><div class=\"box\"><ul>";
foreach (scandir(ROOT . 'plugins') as $item) {
	if (preg_match('/(\w+)Plugin\.php/', $item, $matches)) {
		$meta = loadPlugin($matches[1])->meta();
		$content .= '<li><a href="?script=' . $meta['ID'] . '">' . $meta['name'] . '</a></li>';
	}
}
$content .= "</ul></div></div>";

display('Script List', $content);