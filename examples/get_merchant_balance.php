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

    $params = [
        "merchant_id" => $secret_keys_and_config["merchant_id"],
    ];


    $paykassa = new \Paykassa\PaykassaAPI(
        $secret_keys_and_config["api_id"],
        $secret_keys_and_config["api_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );


    $res = $paykassa->get_merchant_balances(
        $params["merchant_id"]
    );
    
    if ($res["error"]) {        // $res["error"] - true if the error
        echo $res["message"];   // $res["message"] - the text of the error message
        //actions in case of an error
    } else {

            /*
             * BitCoin: [ BTC ],
             * Ethereum: [ ETH ],
             * LiteCoin: [ LTC ],
             * DogeCoin: [ DOGE ],
             * Dash: [ DASH ],
             * BitcoinCash: [ BCH ],
             * Zcash: [ ZEC ],
             * EthereumClassic: [ ETC ],
             * Ripple: [ XRP ],
             * TRON: [ TRX ],
             * Stellar: [ XLM ],
             * BinanceCoin: [ BNB ],
             * TRON_TRC20: [ USDT ],
             * BinanceSmartChain_BEP20: [ USDT, BUSD, USDC, ADA, EOS, BTC, ETH, DOGE, SHIB ],
             * Ethereum_ERC20: [ USDT, BUSD, USDC, SHIB ],
             * Berty: [ USD, RUB ]
             */

            $system = "Ethereum_ERC20";
            $currency = "USDT";

            $label = strtolower(sprintf("%s_%s", $system, $currency));

            $data = $res["data"];

            echo sprintf(
                "Balance for %s %s %s",
                $system,
                $data[$label] ?? "0.0",
                $currency
            );
    }