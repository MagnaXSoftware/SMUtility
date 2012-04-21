<?php

class EncodeAddIn_ROT13 {
	function encode($clearText) {
		return str_rot13($clearText);
	}
}