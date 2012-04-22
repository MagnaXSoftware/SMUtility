<?php

class Algo_ROT13 implements Enc_Decodable {
	public function decode($encodedText) {
		return str_rot13($encodedText);
	}
}
