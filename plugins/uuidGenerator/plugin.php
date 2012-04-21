<?php

class SMU_UuidGenerator {	
	private $types = array(
		array('name' => 'version 3', 'ID' => '3'),
		array('name' => 'version 4', 'ID' => '4', 'default' => true),
		array('name' => 'version 5', 'ID' => '5')
	);
	
	private $ns = array(
		array('name' => 'DNS',	'ID' => 'dns', 'uuid' => '6ba7b810-9dad-11d1-80b4-00c04fd430c8'),
		array('name' => 'URL',	'ID' => 'url', 'uuid' => '6ba7b811-9dad-11d1-80b4-00c04fd430c8'),
		array('name' => 'OID',	'ID' => 'oid', 'uuid' => '6ba7b812-9dad-11d1-80b4-00c04fd430c8'),
		array('name' => 'X500',	'ID' => 'x500', 'uuid' => '6ba7b814-9dad-11d1-80b4-00c04fd430c8')
	);
	
	public function form() {
		return array(
			array(
				'label'	=> 'UUID Type',
				'name'	=> 'type',
				'type'	=> 'radio',
				'value'	=> $this->getOptions()
			),
			array(
				'label'	=> 'Namespace <em>(Only valid for UUID version 3 or 5)</em>',
				'name'	=> 'ns',
				'type'	=> 'radio',
				'value'	=> $this->getNamespaces()
			),
			array(
				'label'	=> 'Name <em>(Only  valid for UUID version 3 or 5)</em>',
				'name'	=> 'name',
				'type'	=> 'string'
			)
		);
	}
	
	public function execute(&$form) {
		$options = array(
			'UUID Version'	=> $this->getOptions(false, $form['type'])
		);
		if ($form['type'] == 3 || $form['type'] == 5) {
			$ns = $this->getNamespaces(false, $form['ns']);
			$options['namespace'] = $ns['name'];
			$options['name'] = $form['name'];
		}
		return array(
			array(
				'label'	=> 'UUID',
				'type'	=> 'string',
				'value'	=> $this->generateUUID($form['type'], ((isset($form['ns'])) ? $this->getNamespaces(false, $form['ns']) : null), ((isset($form['name'])) ? $form['name'] : null))
			),
			'options'	=> $options
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
				return $type['ID'];
			}
		}
		return $this->types[1]['ID'];
	}
	
	function getNamespaces($form = true, $setNS = null) {
		if ($form) {
			$result = array();
			foreach ($this->ns as $ns) {
				$result[] = array('label' => $ns['name'], 'value' => $ns['ID']);
			}
			return $result;
		}

		foreach ($this->ns as $ns) {
			if ($ns['ID'] == $setNS) {
				return $ns;
			}
		}
		throw new Exception('CRITICAL ERROR: NAMESPACE NOT FOUND');
	}

	function generateUUID($type, $ns = null, $name = null) {
		$raw = array(
			'time_low'			=> null,
			'time_mid'			=> null,
			'time_high_and_version'		=> null,
			'clock_sec_and_reserved'	=> null,
			'clock_sec_low'			=> null,
			'node'				=> null
		);
		$uuid = null;
		switch ($type) {
			case 3:
				$this->_uuid_version_3($raw, $ns, $name);
				break;
			case 4:
				$this->_uuid_version_4($raw);
				break;
			case 5:
				$this->_uuid_version_5($raw, $ns, $name);
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
			$uuid .= sprintf('%04x', $node);
		}
		
		return $uuid;
	}
	
	private function _uuid_version_3(&$raw, $ns, $name) {
		$nsbin = $this->_str_to_bin($ns['uuid']);
		$hash = md5($nsbin.$name);

		$raw = array(
			'time_low'			=> array(
				hexdec(substr($hash, 0, 4)),
				hexdec(substr($hash, 4, 4))
			),
			'time_mid'			=> hexdec(substr($hash, 8, 4)),
			'time_high_and_version'		=> (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,
			'clock_sec_and_reserved'	=> (hexdec(substr($hash, 16, 2)) & 0x3f) | 0x80,
			'clock_sec_low'			=> hexdec(substr($hash, 18, 2)),
			'node'				=> array(
				hexdec(substr($hash, 20, 4)),
				hexdec(substr($hash, 24, 4)),
				hexdec(substr($hash, 28, 4)),
			)
		);
	}
	
	private function _uuid_version_4(&$raw) {
		$raw = array(
			'time_low'			=> array(
				mt_rand(0, 0xffff),
				mt_rand(0, 0xffff)
			),
			'time_mid'			=> mt_rand(0, 0xffff),
			'time_high_and_version'		=> mt_rand(0, 0x0fff) | 0x4000,
			'clock_sec_and_reserved'	=> mt_rand(0, 0x3f) | 0x80,
			'clock_sec_low'			=> mt_rand(0, 0xff),
			'node'				=> array(
				mt_rand(0, 0xffff),
				mt_rand(0, 0xffff),
				mt_rand(0, 0xffff),
			)
		);
	}
	
	private function _uuid_version_5(&$raw, $ns, $name) {
		$nsbin = $this->_str_to_bin($ns['uuid']);
		$hash = sha1($nsbin.$name);

		$raw = array(
			'time_low'			=> array(
				hexdec(substr($hash, 0, 4)),
				hexdec(substr($hash, 4, 4))
			),
			'time_mid'			=> hexdec(substr($hash, 8, 4)),
			'time_high_and_version'		=> (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
			'clock_sec_and_reserved'	=> (hexdec(substr($hash, 16, 2)) & 0x3f) | 0x80,
			'clock_sec_low'			=> hexdec(substr($hash, 18, 2)),
			'node'				=> array(
				hexdec(substr($hash, 20, 4)),
				hexdec(substr($hash, 24, 4)),
				hexdec(substr($hash, 28, 4)),
			)
		);
	}

	private function _str_to_bin($uuid) {
		$hex = str_replace(array('-','{','}'), '', $uuid);
		$bin = '';

		for ($i = 0, $max = strlen($hex); $i < $max; $i += 2) {
			$bin .= chr(hexdec($hex[$i].$hex[$i + 1]));
		}

		return $bin;
	}

}
