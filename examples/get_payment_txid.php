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

    $invice_ids = [
        "37411867",
        "37411866",
        "37411863",
    ];

    $res = $paykassa->getTxIdsByInvoiceIds(
        $params["merchant_id"],
        $invice_ids
    );
    
    if ($res["error"]) {        // $res["error"] - true if the error
        echo $res["message"];   // $res["message"] - the text of the error message
        //actions in case of an error
    } else {
        ?><ul><?php
        foreach ($invice_ids as $invoice_id) {
            //IMPORTANT!!!!
            if (!isset($res["data"][$invoice_id])) {
                continue;
            }
            $txids = $res["data"][$invoice_id];
            ?><li><?php
            echo sprintf("Invoice ID: %s<br>", $invoice_id);
            foreach ($txids as $txid) {
                echo sprintf("TXID: %s<br>", $txid);
            }
            ?></li><?php
        }
        ?></ul><?php
    }