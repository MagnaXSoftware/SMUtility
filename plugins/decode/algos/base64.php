<?php

class Algo_BASE64 implements Enc_Decodable {
	public function decode($encodedText) {
		return base64_decode($encodedText);
	}
}
