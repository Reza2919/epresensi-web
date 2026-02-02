<?php

namespace App\Helpers;

class Help {
    public static function encrypt_encode($string) {
        $secret_key = env('SECRET_KEY');
        $encrypted = openssl_encrypt($string, 'AES-256-CBC', $secret_key, 0, $secret_key);
        $encrypted = urlencode($encrypted);

        return $encrypted;
    }

    public static function decrypt_decode($string) {
        $secret_key = env('SECRET_KEY');
        $decrypted = urldecode($string);
        $decrypted = openssl_decrypt($decrypted, 'AES-256-CBC', $secret_key, 0, $secret_key);

        return $decrypted;

    }
}
