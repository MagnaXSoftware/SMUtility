<?php

class Algo_ROT13 implements Enc_Encodable {
	public function encode($clearText) {
		return str_rot13($clearText);
	}
}
