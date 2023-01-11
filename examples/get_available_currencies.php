<?php
    require_once __DIR__ . "/../src/PaykassaCurrency.php";
    //require_once __DIR__ . "/../vendor/autoload.php";

    $res = \Paykassa\PaykassaCurrency::get_available_currencies();

    if ($res["error"]) {
        die($res["message"]);
    } else {
        print_r($res["data"]);
    }