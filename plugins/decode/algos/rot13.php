<?php

class DecodeAddIn_ROT13 {
	function decode($encodedText) {
		return str_rot13($encodedText);
	}
}
