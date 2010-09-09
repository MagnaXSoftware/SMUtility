<?php

class AfroSoftScript_UuidGenerator {
	private $meta = array(
		'ID'		=> 'uuidGenerator',
		'name'		=> 'UUID Generator',
		'author'		=> 'AfroSoft',
		'version'		=> '1.0',
		'description'	=> 'The UUID (or GUID) Generator generates password compliant with most system. It also features some algorithm improvements to prevent mistakes, such as when an \'O\' (upper-case o) and a \'0\' (zero) are confused with each other.'
	);
	
	private $types = array(
		array('name' => 'version 1', 'ID' => '1', 'default' => true),
		array('name' => 'version 2', 'ID' => '2'),
		array('name' => 'version 3', 'ID' => '3'),
		array('name' => 'version 4', 'ID' => '4')
	);

	public function meta() {
		return $this->meta;
	}
	
	public function form() {
		return array(
			array(
				'label'	=> 'UUID Type',
				'name'	=> 'type',
				'type'	=> 'radio',
				'value'	=> $this->getOptions()
			)
		);
	}
	
	public function execute(&$form) {
		return array(
			array(
				'label'	=> 'UUID',
				'type'	=> 'string',
				'value'	=> $this->generateUUID($form['type'])
			),
			'options'	=> array(
				'UUID Version'	=> $this->getOptions(false, $form['type'])
			)
		);
	}
	
	function getOptions($form = true, $setType = null) {
		if ($form) {
			$result = array();
			foreach ($this->types as $type) {
				$result[] = array('label' => $type['name'], 'value' => $type['ID'], 'checked' => $type['default']);
			}
			return $result;
		}

		foreach ($this->types as $type) {
			if ($type['ID'] == $setType) {
				return $type['ID'];
			}
		}
		return $this->types[0]['ID'];
	}

	function generateUUID($type) {
		switch ($type) {
			case 1:
				throw new Exception('Not Yet Implemented');
				break;
			case 2:
				throw new Exception('Not Yet Implemented');
				break;
			case 3:
				throw new Exception('Not Yet Implemented');
				break;
			case 4:
				return $this->_uuid_version_4();
				break;
			default:
				throw new Exception('Unknown option');
				break;
		}
	}
	
	private function _uuid_version_4() {
		return "";
	}
}