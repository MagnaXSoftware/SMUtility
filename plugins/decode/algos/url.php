<?php

class Algo_URL implements Enc_Decodable {
	public function decode($encodedText) {
		return urldecode($encodedText);
	}
}
