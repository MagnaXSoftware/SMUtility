<?php

class HashAddIn_MD5 {	
	public function doHash($clearText) {
		return hash('md5', $clearText);
	}
}