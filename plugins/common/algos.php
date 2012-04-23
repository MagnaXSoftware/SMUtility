<?php

/**
 * Interface that encodable algorithms must implement.
 */
interface Enc_Encodable {

    /**
     * Does the encoding.
     */
    public function encode($clear);
}

/**
 * Interface that decodable algorithms must implement.
 */
interface Enc_Decodable {

    /**
     * Does the decoding.
     */
    public function decode($clear);
}
