<?php
/**
 * SHA-1 Algorithm
 */

/**
 * SHA-1 Algorithm
 *
 * @package Plugin
 * @subpackage Common\Algorithm
 */
class Algo_SHA1 implements Hash_Hashable {

    public function hash($clearText) {
        return hash('sha1', $clearText);
    }
}
