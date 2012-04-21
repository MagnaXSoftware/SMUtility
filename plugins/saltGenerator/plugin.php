<?php

class SMU_SaltGenerator {
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
