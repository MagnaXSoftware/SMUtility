<?php
/**
 * URL Encode Algorithm
 */

/**
 * URL Encode Algorithm
 *
 * @package Plugin
 * @subpackage Common\Algorithm
 */
class Algo_URL implements Enc_Decodable, Enc_Encodable {

    public function encode($clearText) {
        return urlencode($clearText);
    }

    public function decode($encodedText) {
        return urldecode($encodedText);
    }
}
