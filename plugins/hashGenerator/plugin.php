<?php

class SMU_HashGenerator {
	public function form() {
		return array(
			array(
				'label'	=> 'value',
				'name'	=> 'value',
				'type'	=> 'text'
			), 
			array(
				'label'	=> 'Hash',
				'name'	=> 'type',
				'type'	=> 'radio',
				'value'	=> $this->loadHashes()
			)
		);
	}
	
	public function execute(&$form) {
		return array(
			array(
				'label'	=> 'Hashed value',
				'type'	=> 'string',
				'value'	=> $this->generateHash($form['value'], $form['type'])
			),
			'options'	=> array(
				'Hashing algorythm'	=> strtoupper($form['type']),
				'clear text'		=> $form['value']
			)
		);
	}
	
	private function loadHashes() {
		$return = array();
		foreach (scandir(ROOT . 'plugins' . DS . 'hashGenerator' . DS . 'algos') as $item) {
			if (preg_match('/(\w+)\.php/', $item, $matches)) {
				$return[] = array(
					'label'	=> strtoupper($matches[1]),
					'value'	=> $matches[1]
				);
			}
		}
		return $return;
	}

	function generateHash($clear, $type) {
		$file = ROOT . 'plugins' . DS . 'hashGenerator' . DS . 'algos' . DS . $type . '.php';
		if (file_exists($file)) {
			require_once $file;
			$class = "HashAddIn_" . strtoupper($type);
			$obj = new $class;
			return $obj->doHash($clear);
		}
		throw new Exception('Unknown hashing algorithm');
	}
}
