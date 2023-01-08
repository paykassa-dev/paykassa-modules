# Paykassa.pro PHP SCI/API Wrapper(Official).

This is an official module developed by Paykassa.pro for easy integration into PHP-based applications.
Source code of the class files is in the ./src directory. The source code for the examples can be found at ./examples.


## Requirements
It's recommended to use a newer version of PHP. This library was written in a PHP v7.2.34 environment + php-curl modules.

A Paykassa.pro account with **Merchant ID, Merchant Password, API ID, API Password**. You can get the credentials at the pages: [Add merchants](https://paykassa.pro/en/user/shops/add_shop_new/), [Add APIs](https://paykassa.pro/en/user/api/add_api/).


## Usage
### Get a payment address and a QR-code for him.
```php
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
     */
    $params = [
        "amount" => null,
        "system" => "TRON_TRC20",
        "currency" => "USDT",
        "order_id" => "My order id "  . microtime(true),
        "comment" => "My comment",
    ];


    $paykassa = new PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );


    $res = $paykassa->sci_create_address(
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
            $invoice_id = $res['data']['invoice'];

            $address = $res["data"]["wallet"];
            $tag = $res["data"]["tag"];
            $tag_name = $res["data"]["tag_name"];
            $is_tag = $res["data"]["is_tag"];

            $system = $res["data"]["system"];
            $currency = $res["data"]["currency"];

            $display = sprintf("address %s", $address);
            if ($is_tag) {
                $display = sprintf("address %s %s: %s", $address, ucfirst($tag_name), $tag);
            }

            if (null === $params["amount"]) {
                echo sprintf(
                    "Send a money to %s.",
                    htmlspecialchars($display, ENT_QUOTES, "UTF-8")
                );
            } else {
                echo sprintf(
                    "Send %s %s to %s.",
                    $params["amount"],
                    $currency,
                    htmlspecialchars($display, ENT_QUOTES, "UTF-8")
                );
            }


            //Creating QR
            $qr_request = $paykassa->get_qr_link($res['data'], $params["amount"]);
            if (!$qr_request["error"]) {
                echo sprintf(
                    '<br><br>QR Code:<br><img alt="" src="http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=%s">',
                    $qr_request["data"]["link"]
                );
            }
        } else {
            echo sprintf('Test link: <a target="_blank" href="%s">Open link</a>', $res["data"]["url"]);
        }
    }
```


### Check an IPN of a transaction.
```php
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
     */

    $paykassa = new PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );

    $private_hash = $_POST["private_hash"];

    $res = $paykassa->sci_check_transaction_ipn(
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
```

### Get a payment link(create an order).
```php
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


    $res = $paykassa->sci_create_order(
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
```

### Check an IPN of an order.
```php
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

    $paykassa = new PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );

    $private_hash = $_POST["private_hash"];

    $res = $paykassa->sci_check_order_ipn(
        $private_hash
    );

    if ($res['error']) {
        echo $res['message'];
        // actions in case of an error
    } else {
        // actions in case of success
        $id = $res["data"]["order_id"];        // unique numeric identifier of the payment in your system, example: 150800
        $transaction = $res["data"]["transaction"]; // transaction number in the system paykassa: 96401
        $hash = $res["data"]["hash"];               // hash, example: bde834a2f48143f733fcc9684e4ae0212b370d015cf6d3f769c9bc695ab078d1
        $currency = $res["data"]["currency"];       // the currency of payment, for example: DASH
        $system = $res["data"]["system"];           // system, example: Dash
        $address = $res["data"]["address"];         // a cryptocurrency wallet address, for example: Xybb9RNvdMx8vq7z24srfr1FQCAFbFGWLg
        $tag = $res["data"]["tag"];                 // Tag for Ripple and Stellar
        $partial = $res["data"]["partial"];         // set up underpayments or overpayments 'yes' to accept, 'no' - do not take
        $amount = $res["data"]["amount"];    // invoice amount example: 1.0000000

        if ($partial === 'yes') {
            // the amount of application may differ from the amount received, if the mode of partial payment
            // relevant only for cryptocurrencies, default is 'no'
        }

        // your code...

        echo $id.'|success'; // be sure to confirm the payment has been received
    }
```

## Contributing
If during your work with this wrapper you encounter a bug or have a suggestion to help improve it for others, you are welcome to open a Github issue on this repository and it will be reviewed by one of our development team members. The Paykassa.pro bug bounty does not cover this wrapper.

## License
MIT - see LICENSE