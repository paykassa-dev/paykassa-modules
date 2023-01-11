<?php

    require_once __DIR__ . "/../src/PaykassaSCI.php";
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
     */

    $paykassa = new \Paykassa\PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );

    $private_hash = $_POST["private_hash"];

    $res = $paykassa->checkTransactionIpn(
        $private_hash
    );

    if ($res['error']) {
        echo $res['message'];
        // actions in case of an error
    } else {
        // actions in case of success
        $transaction = $res["data"]["transaction"];         // transaction number in the system paykassa: 2431548
        $txid = $res["data"]["txid"];                       // A transaction in a cryptocurrency network, an example: 0xb97189db3555015c46f2805a43ed3d700a706b42fb9b00506fbe6d086416b602
        $shop_id = $res["data"]["shop_id"];                 // Your merchant's number, example: 138
        $id = $res["data"]["order_id"];                     // unique numeric identifier of the payment in your system, example: 150800
        $amount = $res["data"]["amount"];            // received amount, example: 1.0000000
        $fee = $res["data"]["fee"];                  // Payment processing commission: 0.0000000
        $currency = $res["data"]["currency"];               // the currency of payment, for example: DASH
        $system = $res["data"]["system"];                   // system, example: Dash
        $address_from = $res["data"]["address_from"];       // address of the payer's cryptocurrency wallet, example: 0x5d9fe07813a260857cf60639dac710ebb9531a20
        $address = $res["data"]["address"];                 // a cryptocurrency wallet address, for example: Xybb9RNvdMx8vq7z24srfr1FQCAFbFGWLg
        $tag = $res["data"]["tag"];                         // Tag for Ripple and Stellar is an integer
        $confirmations = $res["data"]["confirmations"];     // Current number of network confirmations
        $required_confirmations =
            $res["data"]["required_confirmations"];         // Required number of network confirmations for crediting
        $status = $res["data"]["status"];                   // yes - if the payment is credited
        $static = $res["data"]["static"];                   // Always yes
        $date_update = $res["data"]["date_update"];         // last updated information, example: "2018-07-23 16:03:08"

        $explorer_address_link =
            $res["data"]["explorer_address_link"];          // A link to view information about the address
        $explorer_transaction_link =
            $res["data"]["explorer_transaction_link"];      // Link to view transaction information


        if ($status !== 'yes') {
            //the payment has not been credited yet
            // your code...


            echo $id.'|success'; // confirm receipt of the request
        } else {
            //the payment is credited
            // your code...

            echo $id.'|success'; // be sure to confirm the payment has been received

        }
    }