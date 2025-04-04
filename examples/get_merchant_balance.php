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


    $res = $paykassa->getMerchantBalances(
        $params["merchant_id"]
    );


/* ### SYSTEMS_INFO ### */
    
    if ($res["error"]) {        // $res["error"] - true if the error
        echo $res["message"];   // $res["message"] - the text of the error message
        //actions in case of an error
    } else {

            $system = "Ethereum_ERC20";
            $currency = "USDT";

            $label = mb_strtolower(sprintf("%s_%s", $system, $currency));

            $data = $res["data"];

            echo sprintf(
                "Balance for %s %s %s",
                $system,
                $data[$label] ?? "0.0",
                $currency
            );
    }