<?php
/**
 * RIPEMD-160 Algorithm
 */

/**
 * RIPEMD-160 Algorithm
 *
 * @package Plugin
 * @subpackage Common\Algorithm
 */
class Algo_RIPEMD160 implements Hash_Hashable {

    public function hash($clearText) {
        return hash('repmd160', $clearText);
    }
}
