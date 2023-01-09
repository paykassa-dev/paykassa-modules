<?php

    require_once __DIR__ . "/../src/paykassasci.class.php";


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
        "amount" => "100.00",
        "system" => "BitCoin",
        "currency" => "BTC",
        "order_id" => "My order id "  . microtime(true),
        "comment" => "My comment",
    ];


    $paykassa = new PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );


    $res = $paykassa->create_order(
        $params["amount"],
        $params["system"],
        $params["currency"],
        $params["order_id"],
        $params["comment"]
    );

    if ($res['error']) {
        echo $res['message'];
        // actions in case of an error
    } else {
        if (false === $secret_keys_and_config["config"]["test_mode"]) {
            ?>
            Click the button to make the payment
            <form action="<?php echo $res["data"]["url"]; ?>" method="POST">
                <button>To pay</button>
            </form>
<?php
        } else {
            echo sprintf('Test link: <a target="_blank" href="%s">Open link</a>', $res["data"]["url"]);
        }
    }