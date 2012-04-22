<?php

class Algo_RIPEMD160 implements HashGen_Hashable {
	public function doHash($clearText) {
		return hash('repmd160', $clearText);
	}
}
