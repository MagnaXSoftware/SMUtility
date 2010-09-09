<?php

class AfroSoftScript_HashGenerator {
	private $meta = array(
		'ID'			=> 'hashGenerator',
		'name'			=> 'Hash Generator',
		'author'		=> 'AfroSoft',
		'version'		=> '1.0',
		'description'	=> 'The Hash Generator helps easily hash any value using different hashing algorithms.'
	);

	public function meta() {
		return $this->meta;
	}
	
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
		foreach (scandir(ROOT . 'plugins' . DS . 'hashGeneratorPlugin') as $item) {
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
		if (file_exists(ROOT . 'plugins' . DS . 'hashGeneratorPlugin' . DS . $type . '.php')) {
			require_once ROOT . 'plugins' . DS . 'hashGeneratorPlugin' . DS . $type . '.php';
			$class = "HashAddIn_" . strtoupper($type);
			$obj = new $class;
			return $obj->doHash($clear);
		}
		throw new Exception('Unknown hashing algorithm');
	}
}