<?php
    require_once __DIR__ . "/../src/PaykassaCurrency.php";
    //require_once __DIR__ . "/../vendor/autoload.php";


    $pairs = [
        "BTC_USD",
        "USDT_ETH",
        "XRP_ADA",
    ];


    $res = \Paykassa\PaykassaCurrency::getCurrencyPairs($pairs);

    if ($res["error"]) {
        die($res["message"]);
    } else {
        $map_pairs = [];
        array_map(function ($pairs) use (&$map_pairs) {
            foreach ($pairs as $pair => $value) {
                $map_pairs[$pair] = $value;
            }
        }, $res["data"]);

        foreach ($map_pairs as $pair => $rate) {
            echo sprintf("Pairs - %s -> %s<br>", $pair, $rate);
        }

        $my_pair = "BTC_USD";
        echo sprintf("Request %s -> %s<br>", $my_pair, $map_pairs[$my_pair]);
    }