<?php

class Algo_MD5 implements HashGen_Hashable {
	public function doHash($clearText) {
		return hash('md5', $clearText);
	}
}
