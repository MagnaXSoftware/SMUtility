<?php

class Algo_SHA256 implements HashGen_Hashable {
	public function doHash($clearText) {
		return hash('sha256', $clearText);
	}
}

