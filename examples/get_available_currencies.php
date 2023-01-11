<?php
    require_once __DIR__ . "/../src/PaykassaCurrency.php";
    //require_once __DIR__ . "/../vendor/autoload.php";

    $res = \Paykassa\PaykassaCurrency::getAvailableCurrencies();

    if ($res["error"]) {
        die($res["message"]);
    } else {
        print_r($res["data"]);
    }