<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '.' . DS);

require_once ROOT . 'display.php';
require_once ROOT . 'functions.php';

/* clean input. only allow alphanumerics */
/* we do not clean the input, as plugins might need non alphanumeric characters passed as post
$clean_GET = array();
foreach ($_GET as $key => $value) {
	$clean_GET[preg_replace("/[^a-zA-Z0-9]/", "", $key)] = preg_replace("/[^a-zA-Z0-9]/", "", $value);
}
unset($_GET);
$_GET = $clean_GET;
//*/

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
		$context = false;
	} else {
		try {
			$meta = Load::meta($_GET['script']);
		} catch (Exception $e) {
			display('Error', HTML::grid(HTML::box($e->getMessage())));
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
		display($meta['name'] . ' Info', HTML::grid(HTML::box(HTML::wrap('dl', $content))), $meta);
	} else {
		display($meta['name'] . ' Info', HTML::grid(HTML::box(HTML::wrap('dl', $content))));
	}
	exit();
}


/* second check if a plugin has been specified, if so, load it and run the current view */

if (isset($_GET['script']) && !empty($_GET['script'])) {
	try {
		$obj = Load::plugin($_GET['script']);
		$meta = Load::meta($_GET['script']);
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
		try {
			$content = Generate::result($obj->execute($pluginValues));
		} catch (Exception $e) {
			display('Error', HTML::grid(HTML::box($e->getMessage())));
			exit();
		}
	} else {
		$content = Generate::form($obj->form(), $meta['ID']);
	}
	display($meta['name'], $content, $meta);
	exit();
}


/* nothing matched so display index */
$content = "";
foreach (scandir(ROOT . 'plugins') as $item) {
	try {
		$meta = Load::meta($item);
		
		$content .= '<li><a href="?script=' . $meta['ID'] . '">' . $meta['name'] . '</a> - <a href="?info&amp;script=' . $meta['ID'] . '">Info</a></li>';
	} catch (Exception $e) {}
}

display('Script List', HTML::grid(HTML::box(HTML::wrap('ul',$content))));
