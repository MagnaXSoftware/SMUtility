<?php
/**
 * MD5 Algorithm
 */

/**
 * MD5 Algorithm
 *
 * @package Plugin
 * @subpackage Common\Algorithm
 */
class Algo_MD5 implements Hash_Hashable {

    public function hash($clearText) {
        return hash('md5', $clearText);
    }
}
