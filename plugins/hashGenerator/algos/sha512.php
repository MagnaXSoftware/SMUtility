<?php

class Algo_SHA512 implements HashGen_Hashable {
	public function doHash($clearText) {
		return hash('sha512', $clearText);
	}
}
