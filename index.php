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

header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>Loading... :: SMUtility</title>
<meta charset="UTF-8">
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/text.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/960.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/layout.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/nav.css" media="screen">
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="css/ie6.css" media="screen"><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen"><![endif]-->
<link rel="stylesheet" type="text/css" href="css/custom.css" media="screen">
</head>
<body>
<div class="container_12">
<div class="grid_12">
    <h1 id="branding">Loading...</h1>
</div>
<div class="clear"></div>
<div class="grid_12" id="main">
        <div class="box" id="content">
        </div>
</div>
<div class="clear"></div>
<div id="footer_link" class="grid_12">
    <ul class="nav">
        <li><a href="?">Script List (home)</a></li>
        <li><a href="?info&amp;script=core">System Info</a></li>
    </ul>
</div>
<div class="clear"></div>
<div id="site_info" class="grid_12">
    <div class="box">
        <p>Copyrigth &copy; 2010-2012 AfroSoft</p>
    </div>
</div>
<div class="clear"></div>
</div>
</body>
</html>
