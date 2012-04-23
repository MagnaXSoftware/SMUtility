<?php

class Algo_BASE64 implements Enc_Decodable, Enc_Encodable {

    public function encode($clearText) {
        return base64_encode($clearText);
    }

    public function decode($encodedText) {
        return base64_decode($encodedText);
    }
}
