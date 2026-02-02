<?php
namespace App\Helpers;

use GuzzleHttp\Client;

class SIAP {
    private static $client;

    public static function construct()
    {
        if (is_null(self::$client)) {
            $header['Content-Type'] = 'application/json';
            $baseUrl = env('SIAP_URL','https://siap.kemnaker.go.id/app_integrasi/index.php/api/');
            self::$client = new Client([
                'base_uri' => $baseUrl,
                'headers' => $header,
                'http_errors' => false,
                'verify' => false,
                'auth' => [env('SIAP_USERNAME','traspac'), env('SIAP_PASSWORD','traspaC123')],
            ]);
        }
    }

    public static function post($url, $body, $headers = [])
    {
        try {
            self::construct();
            $attr = ['form_params' => $body];
            if (!empty($headers)) $attr['headers'] = $headers;
        } catch (\Exception $e){
            return $e->getCode();
        }
        return self::$client->post($url, $attr);
    }


    public static function get($url, $body = [], $headers = [])
    {
        self::construct();
        $attr = [];
        if (!empty($body)) $attr['form_params'] = $body;
        if (!empty($headers)) $attr['headers'] = $headers;
        return self::$client->get($url, $attr);
    }

    public static function delete($url, $body = [], $headers = [])
    {
        self::construct();
        $attr = [];
        if (!empty($body)) $attr['form_params'] = $body;
        if (!empty($headers)) $attr['headers'] = $headers;
        return self::$client->delete($url, $attr);
    }
}