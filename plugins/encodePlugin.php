<?php

class AfroSoftScript_Encode {
	private $meta = array(
		'ID'			=> 'encode',
		'name'			=> 'String Encoder',
		'author'		=> 'AfroSoft',
		'version'		=> '1.0',
		'description'	=> 'The String Encoder helps easily encode any value using different encoding algorithms.'
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
				'value'	=> $this->loadEncs()
			)
		);
	}
	
	public function execute(&$form) {
		return array(
			array(
				'label'	=> 'Encoded value',
				'type'	=> 'string',
				'value'	=> $this->encode($form['value'], $form['type'])
			),
			'options'	=> array(
				'Encoding algorythm'	=> strtoupper($form['type']),
				'clear text'		=> $form['value']
			)
		);
	}
	
	private function loadEncs() {
		$return = array();
		foreach (scandir(ROOT . 'plugins' . DS . 'encodePlugin') as $item) {
			if (preg_match('/(\w+)\.php/', $item, $matches)) {
				$return[] = array(
					'label'	=> strtoupper($matches[1]),
					'value'	=> $matches[1]
				);
			}
		}
		return $return;
	}

	function encode($clear, $type) {
		if (file_exists(ROOT . 'plugins' . DS . 'encodePlugin' . DS . $type . '.php')) {
			require_once ROOT . 'plugins' . DS . 'encodePlugin' . DS . $type . '.php';
			$class = "EncodeAddIn_" . strtoupper($type);
			$obj = new $class;
			return $obj->encode($clear);
		}
		throw new Exception('Unknown encoding algorithm');
	}
}