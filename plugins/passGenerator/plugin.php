<?php

class SMU_PassGenerator {
	private $types = array(
		array('name' => 'alphanumeric corrected', 'ID' => '0', 'value' => 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789', 'default' => true),
		array('name' => 'alphanumeric full', 'ID' => '1', 'value' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
		array('name' => 'alphanumeric corrected extended', 'ID' => '2', 'value' => 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789!@#$%&_?*', 'default' => true),
		array('name' => 'alphanumeric full extended', 'ID' => '3', 'value' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&_?*'),
		array('name' => 'alpha corected', 'ID' => '4', 'value' => 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ'),
		array('name' => 'alpha full', 'ID' => '5', 'value' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
		array('name' => 'numeric', 'ID' => '6', 'value' => '0123456789'),
		array('name' => 'extra', 'ID' => '7', 'value' => '!@#$%&_?*')
	);

	public function form() {
		return array(
			array(
				'label'	=> 'length',
				'name'	=> 'length',
				'type'	=> 'integer'
			),
			array(
				'label'	=> 'Password Type',
				'name'	=> 'type',
				'type'	=> 'radio',
				'value'	=> $this->getOptions()
			)
		);
	}
	
	public function execute(&$form) {
		if (!isset($form['length']) || empty($form['length'])) {
			$form['length'] = 8;
		}
		// for now, don't bother type
		$type = $this->getOptions(false, $form['type']);
		return array(
			array(
				'label'	=> 'password',
				'type'	=> 'string',
				'value'	=> $this->generatePass($form['length'], $type['value'])
			),
			'options'	=> array(
				'length'	=> (int)$form['length'],
				'type'		=> $type['name']
			)
		);
	}
	
	function getOptions($form = true, $setType = null) {
		if ($form) {
			$result = array();
			foreach ($this->types as $type) {
				$result[] = array('label' => $type['name'], 'value' => $type['ID'], 'checked' => ((isset($type['default'])) ? $type['default'] : false), 'disabled' => ((isset($type['disabled'])) ? $type['disabled'] : false));
			}
			return $result;
		}

		foreach ($this->types as $type) {
			if ($type['ID'] == $setType) {
				return $type;
			}
		}
		return $this->types[0];
	}

	function generatePass($length, $characterList) {
		$i=0;
		$pass = "";
		while ($i < $length) {
			$pass .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
			$i++;
		}
		return $pass;
	}
}
