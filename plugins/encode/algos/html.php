<?php

class EncodeAddIn_HTML {
	function encode($clearText) {
		return htmlentities(htmlentities($clearText));
	}
}
