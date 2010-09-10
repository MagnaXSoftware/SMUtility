<?php

class AfroSoftScript_Decode {
	private $meta = array(
		'ID'			=> 'decode',
		'name'			=> 'String Decoder',
		'author'		=> 'AfroSoft',
		'version'		=> '1.0',
		'description'	=> 'The String Decoder helps easily decode any value using different encoding algorithms.'
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
				'label'	=> 'Encodings',
				'name'	=> 'type',
				'type'	=> 'radio',
				'value'	=> $this->loadDecs()
			)
		);
	}
	
	public function execute(&$form) {
		return array(
			array(
				'label'	=> 'Decoded value',
				'type'	=> 'string',
				'value'	=> $this->decode($form['value'], $form['type'])
			),
			'options'	=> array(
				'Encoding algorythm'	=> strtoupper($form['type']),
				'Encoded value'		=> $form['value']
			)
		);
	}
	
	private function loadDecs() {
		$return = array();
		foreach (scandir(ROOT . 'plugins' . DS . 'decodePlugin') as $item) {
			if (preg_match('/(\w+)\.php/', $item, $matches)) {
				$return[] = array(
					'label'	=> strtoupper($matches[1]),
					'value'	=> $matches[1]
				);
			}
		}
		return $return;
	}

	function decode($clear, $type) {
		if (file_exists(ROOT . 'plugins' . DS . 'decodePlugin' . DS . $type . '.php')) {
			require_once ROOT . 'plugins' . DS . 'decodePlugin' . DS . $type . '.php';
			$class = "DecodeAddIn_" . strtoupper($type);
			$obj = new $class;
			return $obj->decode($clear);
		}
		throw new Exception('Unknown encoding algorithm');
	}
}