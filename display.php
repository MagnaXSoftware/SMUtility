<?php

/**
 * Various display-related functions
 *
 * @package Core
 * @subpackage Display
 */

/**
 * @deprecated use HTML::display() instead
 * @see HTML::display()
 * @throws Exception
 */
function display($title, $content, &$context = null) {
    throw new Exception('DEPRECATED');
}

/**
 * HTML class handles the generation and display of HTML markup.
 *
 * @package Core
 * @subpackage Display
 */
class HTML {

    /**
     * Generates and display a complete HTML page.
     *
     * @param string $title Title of the page
     * @param string $content The main content of the page
     * @param array $context Metadata on the current plugin, if relevant
     * @return boolean
     */
    public static function display($title, $content, &$context = null) {
        $html = _html_header($title);
        $html .= $content;
        $html .= _html_footer($context);

        header('Content-type: text/html');
        echo $html;
        return true;
    }

    /**
     * Wraps content in an HTML div.box
     *
     * @uses self::wrap
     * @param string $content The content to wrap
     * @return string
     */
    public static function box($content) {
        return self::wrap('div', $content, 'class="box"');
    }

    /**
     * Wraps content in an HTML div.grid_##
     *
     * @uses self::wrap
     * @param string $content The content to wrap
     * @param int $size The size of the grid (1-12)
     * @param mixed $options An array or string of parameters to add to the div
     * @return string
     */
    public static function grid($content, $size = 12, $options = 'id="main"') {
        $options_line = "class=\"grid_{$size}\"";
        if (is_array($options)) {
            foreach ($options as $name => $value) {
                $options_line .= " {$name}=\"{$value}\"";
            }
        }
        if (is_string($options)) {
            $options_line .= " {$options}";
        }
        return self::wrap('div', $content, $options_line);
    }

    /**
     * Wraps content in a specified tag
     *
     * @param string $tag Tag to wrap the content with
     * @param string $content The content to wrap
     * @param mixed $options An array or string of parameters to add to the tag
     * @return string
     */
    static function wrap($tag, $content, $options = null) {
        $options_line = "";
        if (is_array($options)) {
            foreach ($options as $name => $value) {
                $options_line .= " {$name}=\"{$value}\"";
            }
        }
        if (is_string($options)) {
            $options_line .= " {$options}";
        }
        return (empty($options_line)) ? "<{$tag}>{$content}</{$tag}>" : "<{$tag} {$options_line}>{$content}</{$tag}>";
    }

    /**
     * Generates a configuration form for $plugin using specified options.
     *
     * @param array $form
     * @param string $plugin
     * @return string
     * @throws Exception
     */
    public static function generateForm($form, $plugin) {
        $html = "<form id=\"form_{$plugin}\" action=\"?do&amp;script={$plugin}\" method=\"post\" class=\"prefix_2 grid_8 suffix_2 alpha omega block\"><fieldset id=\"fieldset_{$plugin}\"><legend>Configuration options</legend>";
        foreach ($form as $field) {
            if (!isset($field['label'])) {
                throw new Exception('Missing required field', 0);
            }
            $field['label'] = ucfirst($field['label']);
            switch ($field['type']) {
                case 'string':
                    if (!isset($field['name'])) {
                        throw new Exception('Missing required field', 1);
                    }
                    $html .= "<p><label for=\"{$plugin}_{$field['name']}\">{$field['label']}: </label>";
                    $html .= '<input type="text" id="' . "{$plugin}_{$field['name']}" . '" name="' . "{$plugin}_{$field['name']}" . '" ';
                    $html .= (isset($field['disabled']) && $field['disabled']) ? 'disabled="disabled" ' : "";
                    $html .= (isset($field['maxlength'])) ? 'maxlength="' . $field['maxlength'] . '" ' : "";
                    $html .= (isset($field['readonly']) && $field['readonly']) ? 'readonly="readonly" ' : "";
                    $html .= (isset($field['value'])) ? 'value="' . $field['value'] . '" ' : "";
                    $html .= '/>';
                    $html .='</p>';
                    break;
                case 'integer':
                case 'int':
                    if (!isset($field['name'])) {
                        throw new Exception('Missing required field', 1);
                    }
                    $html .= "<p><label for=\"{$plugin}_{$field['name']}\">{$field['label']}: </label>";
                    $html .= '<input type="number" id="' . "{$plugin}_{$field['name']}" . '" name="' . "{$plugin}_{$field['name']}" . '" ';
                    $html .= (isset($field['disabled']) && $field['disabled']) ? 'disabled="disabled" ' : "";
                    $html .= (isset($field['maxlength'])) ? 'maxlength="' . $field['maxlength'] . '" ' : "";
                    $html .= (isset($field['readonly']) && $field['readonly']) ? 'readonly="readonly" ' : "";
                    $html .= (isset($field['value'])) ? 'value="' . $field['value'] . '" ' : "";
                    $html .= '/>';
                    $html .='</p>';
                    break;
                case 'hidden':
                    if (!isset($field['name']) || !isset($field['value'])) {
                        throw new Exception('Missing required field', 2);
                    }
                    $html .= '<input type="hidden" id="' . "{$plugin}_{$field['name']}" . '" name="' . "{$plugin}_{$field['name']}" . '" value="' . $field['value'] . '" />';
                    break;
                case 'text':
                    if (!isset($field['name'])) {
                        throw new Exception('Missing required field', 3);
                    }
                    $html .= "<p><label for=\"{$plugin}_{$field['name']}\">{$field['label']}: </label>";
                    $html .= '<textarea name="' . "{$plugin}_{$field['name']}" . '"';
                    $html .= (isset($field['cols'])) ? ' cols="' . $field['cols'] . '"' : "";
                    $html .= (isset($field['rows'])) ? ' rows="' . $field['rows'] . '"' : "";
                    $html .= (isset($field['disabled']) && $field['disabled']) ? ' disabled="disabled"' : "";
                    $html .= '>';
                    $html .= (isset($field['value'])) ? $field['value'] : "";
                    $html .= '</textarea></p>';
                    break;
                case 'radio':
                    if (!isset($field['name']) || !isset($field['value'])) {
                        throw new Exception('Missing required field', 4);
                    }
                    $html .= "<p><span>{$field['label']}: </span>";
                    $i = 0;
                    foreach ($field['value'] as $item) {
                        if (!isset($item['label']) || !isset($item['value'])) {
                            throw new Exception('Missing required field', 5);
                        }
                        $html .= '<br /><input type="radio" name="' . "{$plugin}_{$field['name']}" . '" id="' . "{$plugin}_{$field['name']}[{$i}]" . '" value="' . $item['value'] . '" ';
                        $html .= (isset($item['checked']) && $item['checked']) ? 'checked="checked" ' : "";
                        $html .= (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled" ' : "";
                        $html .= '/><label class="radio" for="' . "{$plugin}_{$field['name']}[{$i}]" . '">' . $item['label'] . '</label>';
                        $i++;
                    }
                    $html .= "</p>";
                    break;
                case 'list':
                case 'select':
                    if (!isset($field['name']) || !isset($field['value'])) {
                        throw new Exception('Missing required field', 6);
                    }
                    $html .= "<p><label for=\"{$plugin}_{$field['name']}\">{$field['label']}: </label>";
                    $html .= '<select id="' . "{$plugin}_{$field['name']}" . '" name="' . "{$plugin}_{$field['name']}" . '"';
                    $html .= (isset($field['checked']) && $field['checked']) ? ' checked="checked"' : "";
                    $html .= (isset($field['disabled']) && $field['disabled']) ? ' disabled="disabled"' : "";
                    $html .= (isset($field['size'])) ? ' size="' . $field['size'] . '"' : "";
                    $html .= '>';
                    foreach ($field['value'] as $item) {
                        if (!isset($item['value']) || !isset($item['value'])) {
                            throw new Exception('Missing required field', 7);
                        }
                        $html .= '<option value="' . $item['value'] . '"';
                        $html .= (isset($item['selected']) && $item['selected']) ? ' selected="selected"' : "";
                        $html .= (isset($item['disabled']) && $item['disabled']) ? ' disabled="disabled"' : "";
                        $html .= '>' . $item['label'] . '</option>';
                    }
                    $html .= "</select></p>";
                    break;
                case 'check':
                case 'checkbox':
                case 'box':
                    if (!isset($field['name']) || !isset($field['value'])) {
                        throw new Exception('Missing required field', 8);
                    }
                    $html .= "<p><span>{$field['label']}: </span>";
                    $i = 0;
                    foreach ($field['value'] as $item) {
                        if (!isset($item['label']) || !isset($item['value'])) {
                            throw new Exception('Missing required field', 9);
                        }
                        $html .= '<br /><input type="checkbox" name="' . "{$plugin}_{$field['name']}" . '" id="' . "{$plugin}_{$field['name']}[{$i}]" . '" value="' . $item['value'] . '" ';
                        $html .= (isset($item['checked']) && $item['checked']) ? 'checked="checked" ' : "";
                        $html .= (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled" ' : "";
                        $html .= '/><label class="radio" for="' . "{$plugin}_{$field['name']}[{$i}]" . '">' . $item['label'] . '</label>';
                        $i++;
                    }
                    $html .= "</p>";
                    break;
                    break;
                default:
                    throw new Exception('Form input not valid');
                    break;
            }
        }
        $html .= '</fieldset><input type="reset" value="Reset" /><input type="submit" value="Submit" name="submit" id="submit" />';
        $html .= "</form>";
        return self::grid(self::box($html));
    }

    /**
     * Generates the display for the results of the plugin
     *
     * @param array $results
     * @return string
     * @throws Exception
     */
    public static function generateResult($results) {
        if (isset($results['options']) && !empty($results['options']) && is_array($results['options'])) {
            $html = '<div class="grid_4 mid_main"><div class="box"><h2>Configuration Options</h2><div class="block"><dl>';
            foreach ($results['options'] as $key => $value) {
                $key = ucfirst($key);
                $html .= "<dt>{$key}</dt><dd>{$value}</dd>";
            }
            $html .= '</dl></div></div></div>';
            $html .= '<div class="grid_8 mid_main"><div class="box"><h2>Results</h2><div class="block">';
            unset($results['options']);
        } else {
            $html = '<div class="grid_12 mid_main"><div class="box"><h2>Results</h2><div class="block">';
        }
        $html_r = '';
        foreach ($results as $item) {
            if (!isset($item['label'])) {
                throw new Exception('Missing required field \'label\'', 10);
            }
            if (!isset($item['value'])) {
                throw new Exception('Missing required field \'value\'', 11);
            }
            $item['label'] = ucfirst($item['label']);
            $html_r .= "<dt>{$item['label']}</dt><dd>{$item['value']}</dd>";
        }
        $html .= self::wrap('dl', $html_r) . '</div></div></div>';
        return $html;
    }
}

/**
 * @todo deprecate & move to HTML
 */
function _html_header($title) {
    $titleHead = (empty($title)) ? 'SMUtility' : $title . ' :: SMUtility';
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
<div class="grid_12"><h1 id="branding">' . $title . '</h1></div>
<div class="clear"></div>';
}

/**
 * @todo deprecate & move to HTML
 */
function _html_footer(&$meta) {
    $link = "";
    if (!empty($meta)) {
        $link .= '<li><a href="?script=' . $meta['ID'] . '">' . $meta['name'] . '</a></li>';
        $link .= '<li><a href="?info&amp;script=' . $meta['ID'] . '">' . $meta['name'] . ' Info</a></li>';
    }
    return '<div class="clear"></div>
<div id="footer_link" class="grid_12"><ul class="nav"><li><a href="?">Script List (home)</a></li>' . $link . '<li><a href="?info&amp;script=core">System Info</a></li></ul></div>
<div class="clear"></div>
<div id="site_info" class="grid_12"><div class="box"><p>Copyrigth &copy; 2010-2012 AfroSoft</p></div></div>
<div class="clear"></div>
</div>
</body>
</html>';
}
