<?php

class Algo_ROT13 implements Enc_Decodable, Enc_Encodable {

    public function encode($clearText) {
        return str_rot13($clearText);
    }

    public function decode($encodedText) {
        return str_rot13($encodedText);
    }
}
