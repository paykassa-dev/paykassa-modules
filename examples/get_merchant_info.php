<?php

    require_once __DIR__ . "/../src/PaykassaAPI.php";
    //require_once __DIR__ . "/../vendor/autoload.php";


    $secret_keys_and_config = [
        "merchant_id" => "Merchant ID",
        "merchant_password" => "Merchant Password",
        "api_id" => "API ID",
        "api_password" => "API Password",
        "config" => [
            "test_mode" => false,
        ],
    ];

    include_once __DIR__ . "/../config/config-example.php";

    $params = [
        "merchant_id" => $secret_keys_and_config["merchant_id"],
    ];


    $paykassa = new \Paykassa\PaykassaAPI(
        $secret_keys_and_config["api_id"],
        $secret_keys_and_config["api_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );


    $res = $paykassa->getMerchantInfo(
        $params["merchant_id"]
    );
    
    if ($res["error"]) {        // $res["error"] - true if the error
        echo $res["message"];   // $res["message"] - the text of the error message
        //actions in case of an error
    } else {
        var_dump($res["data"]["info"]);
    }