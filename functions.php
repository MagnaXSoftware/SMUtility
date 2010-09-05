<?php

function loadPlugin($name) {
	$file = ROOT . 'plugins' . DS . $name . 'Plugin.php';
	if (!file_exists($file)) {
		display('Error', "The specified plugin '{$name}' hasn't been installed in this system.");
		exit();
	}
	require_once $file;
	$class = 'AfroSoftScript_' . ucfirst($name);
	return new $class;
}

function generateForm($form, $plugin, $name) {
	var_dump($form);
	$html = "<form id=\"form_{$plugin}\" action=\"?do&amp;script={$plugin}\" method=\"post\" class=\"prefix_2 grid_8 suffix_2 alpha omega block\"><fieldset id=\"fieldset_{$plugin}\"><legend>Configuration options</legend>";
	foreach ($form as $field) {
		$field['label'] = ucfirst($field['label']);
		switch($field['type']) {
			case 'string':
			case 'integer':
				if (!isset($field['label']) || !isset($field['name'])) {
					throw new Exception('Missing required field');
				}
				$html .= "<p><label for=\"{$plugin}_{$field['name']}\">{$field['label']}: </label>";
				$html .= '<input type="text" id="' . "{$plugin}_{$field['name']}" . '" id="' . "{$plugin}_{$field['name']}" . '" ';
				$html .= (isset($field['disabled'])) ? 'disabled="disabled" ': "";
				$html .= (isset($field['maxlength'])) ? 'maxlength="' . $field['maxlength'] . '" ': "";
				$html .= (isset($field['readonly'])) ? 'readonly="readonly" ': "";
				$html .= (isset($field['value'])) ? 'value="' . $field['value'] . '" ': "";
				$html .= '/></p>';
				break;
			case 'hidden':
				if (!isset($field['name'])) {
					throw new Exception('Missing required field');
				}
				$html .= '<input type="hidden" id="' . "{$plugin}_{$field['name']}" . '" id="' . "{$plugin}_{$field['name']}" . '" ';
				$html .= (isset($field['value'])) ? 'value="' . $field['value'] . '" ': "";
				$html .= '/>';
				break;
			case 'text':
				if (!isset($field['label']) || !isset($field['name'])) {
					throw new Exception('Missing required field');
				}
				$html .= "<p><label for=\"{$plugin}_{$field['name']}\">{$field['label']}: </label>";
				$html .= '<textarea name="' . "{$plugin}_{$field['name']}" . '"';
				$html .= (isset($field['cols'])) ? ' cols="' . $field['maxlength'] . '"': "";
				$html .= (isset($field['rows'])) ? ' rows="' . $field['maxlength'] . '"': "";
				$html .= (isset($field['disabled'])) ? ' disabled="disabled"': "";
				$html .= (isset($field['readonly'])) ? ' readonly="readonly"': "";
				$html .= '>';
				$html .= (isset($field['value'])) ? $field['value']: "";
				$html .= '</textarea></p>';
				break;
			case 'radio':
				break;
			case 'list':
				break;
			case 'check':
				break;
			default:
				throw new Exception('Form input not valid');
				break;
		}
	}
	$html .= '</fieldset><input type="reset" value="Reset" /><input type="submit" value="Submit" name="submit" id="submit" />';
	$html .= "</form>";
	var_dump($html);
	return $html;
}