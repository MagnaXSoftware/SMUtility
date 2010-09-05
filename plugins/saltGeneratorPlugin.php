<?php

class AfroSoftScript_SaltGenerator {
	private $meta = array(
		'ID'			=> 'saltGenerator',
		'name'			=> 'Salt Generator',
		'author'		=> 'AfroSoft',
		'version'		=> '1.0',
		'description'	=> 'The Salt Generator plugin enables the creation of a valid salt of any arbitrary value bigger than 0.'
	);

	public function meta() {
		return $this->meta;
	}
	
	public function form() {
		return array(
			array(
				'label'	=> 'length',
				'name'	=> 'length',
				'type'	=> 'integer'
			)
		);
	}
	
	public function execute(&$form) {
		if (!isset($form['length']) || empty($form['length'])) {
			$form['length'] = 15;
		}
		return array(
			array(
				'label'	=> 'salt',
				'type'	=> 'string',
				'value'	=> $this->generateSalt((int)$form['length'])
			),
			'options'	=> array(
				'length'	=> (int)$form['length']
			)
		);
	}

	function generateSalt($length) {
		$characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
		$i=0;
		$salt = "";
		while ($i <= $length) {
			$salt .= $characterList{mt_rand(0,strlen($characterList))};
			$i++;
		}
		return $salt;
	}
}