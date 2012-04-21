<?php

class SMU_Decode {
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
		foreach (scandir(ROOT . 'plugins' . DS . 'decodePlugin' . DS . 'algos') as $item) {
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
		$file = ROOT . 'plugins' . DS . 'decodePlugin' . DS . 'algos' . DS . $type . '.php';
		if (file_exists($file)) {
			require_once $file;
			$class = "DecodeAddIn_" . strtoupper($type);
			$obj = new $class;
			return $obj->decode($clear);
		}
		throw new Exception('Unknown encoding algorithm');
	}
}
