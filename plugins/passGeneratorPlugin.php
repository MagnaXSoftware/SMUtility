<?php

class AfroSoftScript_PassGenerator {
	private $meta = array(
		'ID'		=> 'passGenerator',
		'name'		=> 'Password Generator',
		'author'		=> 'AfroSoft',
		'version'		=> '1.0',
		'description'	=> 'The Password Generator generates password compliant with most system. It also features some algorithm improvements to prevent mistakes such as an \'O\' (upper-case o) and a \'0\' (zero) are confused with each other.'
	);
	
	private $fields = array(
		'length'		=> 'length'
	);

	public function meta() {
		return $this->meta;
	}
	
	public function form() {
		return array(
			array(
				'name'	=> $this->fields['length'],
				'type'	=> 'integer'
			)
		);
	}
	
	public function execute(&$form) {
		if (!isset($form[$this->fields['length']]) || empty($form[$this->fields['length']])) {
			$form[$this->fields['length']] = 8;
		}
		// for now, don't bother type
		$type = 0;
		return array(
			array(
				'type'	=> 'string',
				'value'	=> $this->generatePass($form[$this->fields['length']], $type)
			)
		);
	}

	function generatePass($length, $type) {
		switch ($type) {
			case 0:
			default:
				// alphanumeric no mistake
				$characterList = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789";
				break;
			case 1:
				// alphanumeric full
				$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				break;
			case 2:
				// alphanumeric no mistake extended
				$characterList = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789!@#$%&_";
				break;
			case 3:
				// alphanumeric full extended
				$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&_";
				break;
			case 4:
				// alpha no mistake
				$characterList = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ";
				break;
			case 5:
				// alpha full
				$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
				break;
			case 6:
				// numeric
				$characterList = "0123456789";
				break;
		}
		$i=0;
		$pass = "";
		while ($i <= $length) {
			$pass .= $characterList{mt_rand(0,strlen($characterList))};
			$i++;
		}
		return $pass;
	}
}