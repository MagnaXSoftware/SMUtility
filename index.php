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

header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>Loading... :: SMUtility</title>
<meta charset="UTF-8">
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="<?php echo ASSET_DIR ?>css/reset.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?php echo ASSET_DIR ?>css/text.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?php echo ASSET_DIR ?>css/960.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?php echo ASSET_DIR ?>css/layout.css" media="screen">
<link rel="stylesheet" type="text/css" href="<?php echo ASSET_DIR ?>css/nav.css" media="screen">
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="<?php echo ASSET_DIR ?>css/ie6.css" media="screen"><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="<?php echo ASSET_DIR ?>css/ie.css" media="screen"><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo ASSET_DIR ?>css/custom.css" media="screen">
<script src="<?php echo ASSET_DIR?>js/jquery-1.7.2.min.js"></script>
<script src="<?php echo ASSET_DIR?>js/custom.js"></script>
    </head>
<body>
<div class="container_12">
<div class="grid_12">
    <h1 id="branding">Loading...</h1>
</div>
<div class="clear"></div>
<div class="grid_12" id="main">
    <div class="box center" id="loader">
        <img src="<?php echo ASSET_DIR ?>img/loading.gif" height="32px" width="32px">
    </div>
    <div class="box" id="content" style="display: none;"></div>
    <noscript>
        <div class="box">
            <p>Use of this interface requires javascript to be enabled.</p>
            <p>If you wish not to enable javascript, please use the <a href="html.php">html interface</a>.</p>
        </div>
    </noscript>
</div>
<div class="clear"></div>
<div id="footer_link" class="grid_12">
    <ul class="nav">
        <li><a id="home" href="html.php?">Script List (home)</a></li>
        <li><a id="sys_info" href="html.php?info&amp;script=core">System Info</a></li>
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
