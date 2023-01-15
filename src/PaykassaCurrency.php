<?php

namespace Paykassa;
class PaykassaCurrency
{
    private static function
    ok(
        string $message,
        array  $data = []
    ): array
    {
        return [
            'error' => false,
            'message' => $message,
            'data' => $data,
        ];
    }

    private static function
    err(
        string $message,
        array  $data = []
    ): array
    {
        return [
            'error' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    private static function
    request(
        string $action,
        string $method = "GET",
        array  $params = []
    ): array
    {

        $base_url = "https://currency.paykassa.pro/";

        $curl = curl_init(
            sprintf("%s%s%s",
                $base_url,
                $action, "GET" === $method ? sprintf("?%s", http_build_query($params)) : ""
            )
        );
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (!defined('CURL_HTTP_VERSION_2_0')) {
            define('CURL_HTTP_VERSION_2_0', 3);
        }

        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-type: application/x-www-form-urlencoded',
        ]);

        if ("POST" === $method) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($curl, CURLOPT_POST, 1);
        }

        $res = curl_exec($curl);
        if (false === $res) {
            return self::err(
                curl_error($curl)
            );
        }
        $data = json_decode($res, true);
        if (null === $data || false === $data) {
            return self::err(
                'Json parse error: ' . json_last_error_msg()
            );
        }

        if (!isset($data['error'], $data['message'])) {
            return self::err('Bad response format');
        }

        return $data;
    }

    public static function getCurrencyPairs(array $pairs = []): array
    {
        return self::request("pairs.php", "POST", [
            "pairs" => $pairs,
        ]);
    }

    public static function getAvailableCurrencies(): array
    {
        return self::request("currency.php");
    }
}
