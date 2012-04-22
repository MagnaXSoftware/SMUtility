<?php

class Algo_URL implements Enc_Encodable{
	public function encode($clearText) {
		return urlencode($clearText);
	}
}
