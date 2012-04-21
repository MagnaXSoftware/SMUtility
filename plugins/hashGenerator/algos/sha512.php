<?php

class HashAddIn_SHA512 {
	public function doHash($clearText) {
		return hash('sha512', $clearText);
	}
}
