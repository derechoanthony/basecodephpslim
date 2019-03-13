<?php

namespace App\Helper;

class CCiper
{
    /**
     * Key ecnryption & decryption for the Link
     *
     * @param String $action
     * @param String $string
     * @return String
     */
    public function cipher(String $action, String $string)
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = env('JFC-Franch!S1n9');
        $secret_iv = env('JFC-Franch!S1n9');

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } elseif ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}
