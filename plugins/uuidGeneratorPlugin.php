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
		array('name' => 'version 2', 'ID' => '2'),
		array('name' => 'version 3', 'ID' => '3'),
		array('name' => 'version 4', 'ID' => '4', 'default' => true)
	);
	
	private $ns = array(
		array('name' => 'DNS',	'ID' => 'dns', 'uuid' => ''),
		array('name' => 'URL',	'ID' => 'url', 'uuid' => ''),
		array('name' => 'OID',	'ID' => 'oid', 'uuid' => ''),
		array('name' => 'X500',	'ID' => 'x500', 'uuid' => '')
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
		return $this->types[2]['ID'];
	}

	function generateUUID($type) {
		$raw = array(
			'time_low'					=> null,
			'time_mid'					=> null,
			'time_high_and_version'		=> null,
			'clock_sec_and_reserved'	=> null,
			'clock_sec_low'				=> null,
			'node'						=> null
		);
		$uuid = null;
		switch ($type) {
			case 2:
				throw new Exception('Not Yet Implemented');
				break;
			case 3:
				throw new Exception('Not Yet Implemented');
				break;
			case 4:
				$this->_uuid_version_4($raw);
				break;
			default:
				throw new Exception('Unknown option');
				break;
		}
		
		/*
		 * PHP doesn't support 32-bit unsigned integers, therefore, 
		 * the time_low field must be separated in two.
		 */
		foreach ($raw['time_low'] as $tm) {
			$uuid .= sprintf('%04x', $tm);
		}
		$uuid .= sprintf('-%04x-%04x-%02x%02x-',
					   $raw['time_mid'],
					   $raw['time_high_and_version'],
					   $raw['clock_sec_and_reserved'],
					   $raw['clock_sec_low']
					   );
		foreach ($raw['node'] as $node) {
			$uuid .= sprintf('%02x', $node);
		}
		
		var_dump($uuid, $raw);
		
		return $uuid;
	}
	
	private function _uuid_version_4(&$raw) {
		$raw = array(
			'time_low'					=> array(
				mt_rand(0, 0xffff),
				mt_rand(0, 0xffff)
			),
			'time_mid'					=> mt_rand(0, 0xffff),
			'time_high_and_version'		=> mt_rand(0, 0x0fff) | 0x4000,
			'clock_sec_and_reserved'	=> mt_rand(0, 0x3f) | 0x80,
			'clock_sec_low'				=> mt_rand(0, 0xff),
			'node'						=> array(
				mt_rand(0, 0xff),
				mt_rand(0, 0xff),
				mt_rand(0, 0xff),
				mt_rand(0, 0xff),
				mt_rand(0, 0xff),
				mt_rand(0, 0xff)
			)
		);
	}
}