<?php
/**
 * Algorithm interfaces
 *
 * @package Plugin
 * @subpackage Common
 */

/**
 * Interface that encodable algorithms must implement.
 *
 * @package Plugin
 * @subpackage Encode
 */
interface Enc_Encodable {

    /**
     * Does the encoding.
     * @param string $clear String to encode
     * @return string
     */
    public function encode($clear);
}

/**
 * Interface that decodable algorithms must implement.
 *
 * @package Plugin
 * @subpackage Decode
 */
interface Enc_Decodable {

    /**
     * Does the decoding.
     * @param string $encoded String to decode
     * @return string
     */
    public function decode($encoded);
}

/**
 * Interface that hashable algorithms must implement.
 *
 * @package Plugin
 * @subpackage HashGenerator
 */
interface Hash_Hashable {

    /**
     * Does the hashing.
     * @param string $clear String to hash
     * @return string
     */
    public function hash($clear);
}
