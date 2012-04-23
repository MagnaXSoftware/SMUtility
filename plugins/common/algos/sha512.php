<?php
/**
 * SHA-512 Algorithm
 */

/**
 * SHA-512 Algorithm
 *
 * @package Plugin
 * @subpackage Common\Algorithm
 */
class Algo_SHA512 implements Hash_Hashable {
	public function hash($clearText) {
		return hash('sha512', $clearText);
	}
}
