<?php

class HashAddIn_SHA256 {	
	public function doHash($clearText) {
		return hash('sha256', $clearText);
	}
}