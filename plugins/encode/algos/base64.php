<?php

class Algo_BASE64 implements Enc_Encodable {
	public function encode($clearText) {
		return base64_encode($clearText);
	}
}
