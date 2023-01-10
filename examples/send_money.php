<?php

    require_once __DIR__ . "/../src/PaykassaAPI.php";


    $secret_keys_and_config = [
        "merchant_id" => "Merchant ID",
        "merchant_password" => "Merchant Password",
        "api_id" => "API ID",
        "api_password" => "API Password",
        "config" => [
            "test_mode" => false,
        ],
    ];

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
    $params = [
        "merchant_id" => $secret_keys_and_config["merchant_id"],
        "wallet" => [
            "address" => "TTEAUAzhSFomrv9P7Q5AcqTchWHBq745gh",
            "tag" => "",
        ],
        "amount" => "5.123456",
        "system" => "TRON_TRC20",
        "currency" => "USDT",
        "comment" => "My comment",
        "priority" => "medium", // low, medium, high
    ];


    $paykassa = new PaykassaAPI(
        $secret_keys_and_config["api_id"],
        $secret_keys_and_config["api_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );


    $res = $paykassa->send_money(
        $params["merchant_id"],
        $params["wallet"],
        $params["amount"],
        $params["system"],
        $params["currency"],
        $params["comment"],
        $params["priority"]
    );
    
    if ($res["error"]) {        // $res["error"] - true if the error
        echo $res["message"];   // $res["message"] - the text of the error message
        //actions in case of an error
    } else {
        //actions in case of success
        $merchant_id = $res["data"]["shop_id"];                     // merchant id that you originally made payment, example 122
        $transaction = $res["data"]["transaction"];                 // transaction number of the payment, example 130236
        $txid = $res["data"]["txid"];                               // txid 70d6dc6841782c6efd8deac4b44d9cc3338fda7af38043dd47d7cbad7e84d5dd can be empty
        // In this case, the information about the transaction can be obtained using a universal link from the Explorer_Transaction_Link field, see below
        $payment_id = $res["data"]["payment_id"];                   // Payment transaction number in the payment system, example 478937139
        $amount = $res["data"]["amount"];                           // the amount of the payment, how much was written off from the balance of the merchant 0.42
        $amount_pay = $res["data"]["amount_pay"];                   // the amount of the payment, as it is the user, example: 0.41
        $system = $res["data"]["system"];                           // the system of payment, which was made the payment, example: Bitcoin
        $currency = $res["data"]["currency"];                       // the payment currency, for example: BTC
        $number = $res["data"]["number"];                           // the address where you sent the funds
        $comission_percent = $res["data"]["shop_comission_percent"];// the transfer fee percentage, example: 1.5
        $comission_amount = $res["data"]["shop_comission_amount"];  // the transfer fee amount, example: 1.00
        $paid_commission = $res["data"]["paid_commission"];         // who paid for the Commission, for example: shop
    
    
        $explorer_address_link =
            $res["data"]["explorer_address_link"];          // A link to view information about the address
        $explorer_transaction_link =
            $res["data"]["explorer_transaction_link"];      // Link to view transaction information


        echo sprintf(
            'We have sent the %s %s %s to <a target="_blank" href="%s">%s</a>. The txid is <a target="_blank" href="%s">%s</a>',
            $system,
            $amount,
            $currency,
            $explorer_address_link,
            $number,
            $explorer_transaction_link,
            $txid
        );
    }