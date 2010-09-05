<?php

function display($title, $content, &$context = null) {
	$html = _html_header($title);
	$html .= '<div class="grid_12"><div class="box">';
	$html .= $content;
	$html .= '</div></div>';
	$html .= _html_footer($context);
	
	header('Content-type: text-html');
	echo $html;
	return true;
}

function _html_header($title) {
	$titleHead = (empty($title)) ? 'AfroSoft Script Management Utility' : $title . ' :: AfroSoft Script Management Utility';
	return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<title>' . $titleHead . '</title>
<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/text.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/960.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/nav.css" media="screen" />
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="css/ie6.css" media="screen" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen" /><![endif]-->
<link rel="stylesheet" type="text/css" href="css/custom.css" media="screen" />
</head>
<body>
<div class="container_12">
<div class="grid_12"><h1 id="branding">'. $title .'</h1></div>
<div class="clear"></div>';
}

function _html_footer(&$context) {
	$link = "";
	if (!empty($context) && method_exists($context, 'meta')) {
		$meta = $context->meta();
		$link = '<li><a href="?info&amp;script=' . $meta['ID'] . '">' . $meta['name'] . ' Info</a></li>';
	}
	return '<div class="clear"></div>
<div id="footer_link" class="grid_12"><ul class="nav"><li><a href="?">Script List (home)</a></li>' . $link . '<li><a href="?info&amp;script=system">System Info</a></li></ul></div>
<div class="clear"></div>
<div id="site_info" class="grid_12"><div class="box"><p>Copyrigth &copy; 2010 AfroSoft</p></div></div>
<div class="clear"></div>
</div>
</body>
</html>';
}