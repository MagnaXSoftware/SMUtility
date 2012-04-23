<?php

class Algo_HTML implements Enc_Encodable {

    public function encode($clearText) {
        return htmlentities(htmlentities($clearText));
    }
}
