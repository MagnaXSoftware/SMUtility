<?php
/**
 * SHA-256 Algorithm
 */

/**
 * SHA-256 Algorithm
 *
 * @package Plugin
 * @subpackage Common\Algorithm
 */
class Algo_SHA256 implements Hash_Hashable {
	public function hash($clearText) {
		return hash('sha256', $clearText);
	}
}

