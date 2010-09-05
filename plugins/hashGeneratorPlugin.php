<?php

class AfroSoftScript_HashGenerator {
	private $meta = array(
		'ID'			=> 'hashGenerator',
		'name'			=> 'Hash Generator',
		'author'		=> 'AfroSoft',
		'version'		=> '1.0',
		'description'	=> 'The Hash Generator helps easily hash any value using different hashing algorithms.'
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
			$form[$this->fields['length']] = 15;
		}
		return array(
			array(
				'type'	=> 'string',
				'value'	=> $this->generateHash($form[$this->fields['length']])
			)
		);
	}

	function generateHash($length) {
		$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$i=0;
		$salt = "";
		while ($i <= $length) {
			$salt .= $characterList{mt_rand(0,strlen($characterList))};
			$i++;
		}
		return $salt;
	}
}