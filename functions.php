<?php

function loadPlugin($name) {
	$file = ROOT . 'plugins' . DS . $name . 'Plugin.php';
	if (!file_exists($file)) {
		display('Error', "<div class=\"grid_12\"><div class=\"box\">The specified plugin '{$name}' hasn't been installed in this system.</div></div>");
		exit();
	}
	require_once $file;
	$class = 'AfroSoftScript_' . ucfirst($name);
	return new $class;
}

function generateForm($form, $plugin) {
	$html = "<div class=\"grid_12\"><div class=\"box\"><form id=\"form_{$plugin}\" action=\"?do&amp;script={$plugin}\" method=\"post\" class=\"prefix_2 grid_8 suffix_2 alpha omega block\"><fieldset id=\"fieldset_{$plugin}\"><legend>Configuration options</legend>";
	foreach ($form as $field) {
		if (!isset($field['label'])) {
			throw new Exception('Missing required field', 0);
		}
		$field['label'] = ucfirst($field['label']);
		switch($field['type']) {
			case 'string':
			case 'integer':
			case 'int':
				if (!isset($field['name'])) {
					throw new Exception('Missing required field', 1);
				}
				$html .= "<p><label for=\"{$plugin}_{$field['name']}\">{$field['label']}: </label>";
				$html .= '<input type="text" id="' . "{$plugin}_{$field['name']}" . '" name="' . "{$plugin}_{$field['name']}" . '" ';
				$html .= (isset($field['disabled']) && $field['disabled']) ? 'disabled="disabled" ': "";
				$html .= (isset($field['maxlength'])) ? 'maxlength="' . $field['maxlength'] . '" ': "";
				$html .= (isset($field['readonly']) && $field['readonly']) ? 'readonly="readonly" ': "";
				$html .= (isset($field['value'])) ? 'value="' . $field['value'] . '" ': "";
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
				$html .= (isset($field['cols'])) ? ' cols="' . $field['cols'] . '"': "";
				$html .= (isset($field['rows'])) ? ' rows="' . $field['rows'] . '"': "";
				$html .= (isset($field['disabled']) && $field['disabled']) ? ' disabled="disabled"': "";
				$html .= '>';
				$html .= (isset($field['value'])) ? $field['value']: "";
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
					$html .= (isset($item['checked']) && $item['checked']) ? 'checked="checked" ': "";
					$html .= (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled" ': "";
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
				$html .= (isset($field['checked']) && $field['checked']) ? ' checked="checked"': "";
				$html .= (isset($field['disabled']) && $field['disabled']) ? ' disabled="disabled"': "";
				$html .= (isset($field['size'])) ? ' size="' . $field['size'] . '"': "";
				$html .= '>';
				foreach ($field['value'] as $item) {
					if (!isset($item['value']) || !isset($item['value'])) {
						throw new Exception('Missing required field', 7);
					}
					$html .= '<option value="' . $item['value'] . '"';
					$html .= (isset($item['selected']) && $item['selected']) ? ' selected="selected"': "";
					$html .= (isset($item['disabled']) && $item['disabled']) ? ' disabled="disabled"': "";
					$html .= '>' . $item['label'] .'</option>';
				}
				$html .= "</select></p>";
				break;
			case 'check':
			case 'checkbox':
			case 'box':
			case 'radio':
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
					$html .= (isset($item['checked']) && $item['checked']) ? 'checked="checked" ': "";
					$html .= (isset($item['disabled']) && $item['disabled']) ? 'disabled="disabled" ': "";
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
	$html .= "</form></div></div>";
	return $html;
}

function generateResult($results) {
	if (isset($results['options']) && is_array($results['options']) && !empty($results['options'])) {
		$html = '<div class="grid_4"><div class="box"><h2>Configuration Options</h2><div class="block"><dl>';
		foreach ($results['options'] as $key => $value) {
			$key = ucfirst($key);
			$html .= "<dt>{$key}</dt><dd>{$value}</dd>";
		}
		$html .= '</dl></div></div></div>';
		$html .= '<div class="grid_8"><div class="box"><h2>Results</h2><div class="block">';
		unset($results['options']);
	} else {
		$html = '<div class="grid_12"><div class="box"><h2>Results</h2><div class="block">';
	}
	$html .= '<dl>';
	foreach ($results as $item) {
		$item['label'] = ucfirst($item['label']);
		$html .= "<dt>{$item['label']}</dt><dd>{$item['value']}</dd>";
	}
	$html .= '</dl></div></div></div>';
	return $html;
}