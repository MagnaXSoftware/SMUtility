<?php

class HashAddIn_RIPEMD160 {
	public function doHash($clearText) {
		return hash('repmd160', $clearText);
	}
}
