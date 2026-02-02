<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class API
{
    private static $client;

    public static function construct()
    {
        if (is_null(self::$client)) {
            $header['Content-Type'] = 'application/json';
            if (@session('token')) {
                $header['Authorization'] = 'Bearer ' . session('token');
            }

            $baseUrl = env('API_URL', 'localhost:2400/');


            self::$client = new Client([
                'base_uri' => $baseUrl,
                'headers' => $header,
                'http_errors' => false
            ]);
        }
    }

    public static function post($url, $body, $headers = [])
    {
        try {
            self::construct();
            $attr = ['form_params' => $body];
            if (!empty($headers)) $attr['headers'] = $headers;
        } catch (\Exception $e) {
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
