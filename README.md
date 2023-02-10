# Paykassa.pro PHP SCI/API Wrapper(Official).

This is an official module developed by Paykassa.pro for easy integration into PHP-based applications.
Source code of the class files is in the ./src directory. The source code for the examples can be found at ./examples.


## Requirements
It's recommended to use a newer version of PHP. This library was written in a PHP v7.1+ environment + php-curl, php-json, php-mbstring modules.

A Paykassa.pro account with **Merchant ID, Merchant Password, API ID, API Password**. You can get the credentials at the pages: [Add merchant](https://paykassa.pro/en/user/shops/add_shop_new/), [Add API](https://paykassa.pro/en/user/api/add_api/).

## Installation

Package available on [Composer](https://packagist.org/packages/paykassa-dev/paykassa).

If you're using Composer to manage dependencies, you can use

```bash
$ composer require paykassa-dev/paykassa
```

## Test examples with Docker

To quickly run examples you need to install Docker, git and make utility.

**Step 1:**

```bash
$ git clone https://github.com/paykassa-dev/paykassa-modules.git
$ cd paykassa-modules/
```

**Step 2:**

Setup config with credentials *config/config-example.php*

**Step 3:**

```bash
$ make run
```

**Step 4:**

Open examples in your browser *examples/*


**Example:**

Follow the link [http://localhost/examples/custom_payment_page.php](http://localhost/examples/custom_payment_page.php)

**Stop server:**

```bash
$ make stop
```

## Usage


### Custom payment page
```php
<?php

    require_once __DIR__ . "/../src/PaykassaSCI.php";


    $secret_keys_and_config = [
        "merchant_id" => "Merchant ID",
        "merchant_password" => "Merchant Password",
        "api_id" => "API ID",
        "api_password" => "API Password",
        "config" => [
            "test_mode" => false,
        ],
    ];


    $paykassa = new \Paykassa\PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );

    if ("GET" === $_SERVER['REQUEST_METHOD']) {
        $list = \Paykassa\PaykassaSCI::getPaymentSystems("crypto");
?>
        <form action="" method="POST">
            <label>Select payment direction</label>
            <select name="pscur">
                <option value="">---</option>
                <?php foreach ($list as $item) { ?>
                    <?php foreach ($item["currency_list"] as $currency) { ?>
                        <option value="<?php echo htmlspecialchars(
                            sprintf("%s_%s", mb_strtolower($item["system"]), mb_strtolower($currency)),
                            ENT_QUOTES, "UTF-8"); ?>">
                            <?php echo htmlspecialchars(sprintf("%s %s", $item["display_name"], $currency),
                                ENT_QUOTES, "UTF-8"); ?>
                        </option>
                    <?php } ?>
                <?php } ?>
            </select>

            <button>To pay</button>
        </form>

<?php
        exit(0);
    }

    @list($system, $currency) = preg_split('~_(?=[^_]*$)~', $_POST["pscur"]);

    $params = [
        "amount" => null,
        "system" => $system,
        "currency" => $currency,
        "order_id" => "My order id "  . microtime(true),
        "comment" => "My comment",
    ];

    $res = $paykassa->createAddress(
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
                $display = sprintf("address %s %s: %s", $address, mb_convert_case($tag_name, MB_CASE_TITLE), $tag);
            }

            if (null === $params["amount"]) {
                echo sprintf(
                    "Send a money to the %s %s.",
                    $system,
                    htmlspecialchars($display,ENT_QUOTES, "UTF-8")
                );
            } else {
                echo sprintf(
                    "Send %s %s to the %s %s.",
                    $params["amount"],
                    $currency,
                    $system,
                    htmlspecialchars($display, ENT_QUOTES, "UTF-8")
                );
            }


            //Creating QR
            $qr_request = $paykassa->getQrLink($res['data'], $params["amount"]);
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

**Example:**
Follow the link [http://localhost/examples/custom_payment_page.php](http://localhost/examples/custom_payment_page.php)

### Get a payment address and a QR-code for him
```php
<?php

    require_once __DIR__ . "/../src/PaykassaSCI.php";


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

    $paykassa = new \Paykassa\PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );


    $res = $paykassa->createAddress(
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
                $display = sprintf("address %s %s: %s", $address, mb_convert_case($tag_name, MB_CASE_TITLE), $tag);
            }

            if (null === $params["amount"]) {
                echo sprintf(
                    "Send a money to the %s %s.",
                    $system,
                    htmlspecialchars($display,ENT_QUOTES, "UTF-8")
                );
            } else {
                echo sprintf(
                    "Send %s %s to the %s %s.",
                    $params["amount"],
                    $currency,
                    $system,
                    htmlspecialchars($display, ENT_QUOTES, "UTF-8")
                );
            }


            //Creating QR
            $qr_request = $paykassa->getQrLink($res['data'], $params["amount"]);
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

**Example:**
Follow the link [http://localhost/examples/create_address.php](http://localhost/examples/create_address.php)

### Check an IPN of a transaction
```php
<?php

    require_once __DIR__ . "/../src/PaykassaSCI.php";


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
```

**Example:**
Follow the link [http://localhost/examples/processing_ipn_for_transaction.php](http://localhost/examples/processing_ipn_for_transaction.php)

### Get a payment link(create an order)
```php
<?php

    require_once __DIR__ . "/../src/PaykassaSCI.php";


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


    $paykassa = new \Paykassa\PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );


    $res = $paykassa->createOrder(
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

**Example:**
Follow the link [http://localhost/examples/create_payment_link.php](http://localhost/examples/create_payment_link.php)

### Check an IPN of an order
```php
<?php

    require_once __DIR__ . "/../src/PaykassaSCI.php";


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

    $paykassa = new \Paykassa\PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );

    $private_hash = $_POST["private_hash"];

    $res = $paykassa->checkOrderIpn(
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

**Example:**
Follow the link [http://localhost/examples/processing_ipn_for_order.php](http://localhost/examples/processing_ipn_for_order.php)

### Send a money
```php
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


    $paykassa = new \Paykassa\PaykassaAPI(
        $secret_keys_and_config["api_id"],
        $secret_keys_and_config["api_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );


    $res = $paykassa->sendMoney(
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
        $commission_percent = $res["data"]["shop_commission_percent"];// the transfer fee percentage, example: 1.5
        $commission_amount = $res["data"]["shop_commission_amount"];  // the transfer fee amount, example: 1.00
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
```

**Example:**
Follow the link [http://localhost/examples/send_money.php](http://localhost/examples/send_money.php)

### Get a merchant balance
```php
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

            $label = mb_strtolower(sprintf("%s_%s", $system, $currency));

            $data = $res["data"];

            echo sprintf(
                "Balance for %s %s %s",
                $system,
                $data[$label] ?? "0.0",
                $currency
            );
    }
```

**Example:**
Follow the link [http://localhost/examples/get_merchant_balance.php](http://localhost/examples/get_merchant_balance.php)

### Get a merchant history
```php
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


    $params = [
        "type" => "pay_in", //pay_in, pay_out
        "shop_id" => $secret_keys_and_config["merchant_id"],
        "page_num" => 0,
        "status" => "yes", //yes - success, no - waiting or unsuccessful
        "datetime_start" => "2022-12-08T19:58:00+0000", //ISO 8601
        "datetime_end" => '2023-01-12T19:58:00+0000', //ISO 8601 - date("c", time())
    ];


    $paykassa = new \Paykassa\PaykassaAPI(
        $secret_keys_and_config["api_id"],
        $secret_keys_and_config["api_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );


    $res = $paykassa->getHistory(
        $params
    );
    $index = 1;
    do {
        if ($res["error"]) {        // $res["error"] - true if the error
            echo $res["message"];   // $res["message"] - the text of the error message
            //actions in case of an error
            break;
        } else {
            foreach ($res["data"]["list"] as $item) {
                echo sprintf("<b>Index - %d, Page - %d Total page - %d</b><br>", $index, $params["page_num"] + 1, $res["data"]["page_count"]);
                print_r($item);
                echo "<br>";
                $index += 1;
            }
        }
        $params["page_num"] += 1;
    } while ($params["page_num"] < $res["data"]["page_count"]);

```

**Example:**
Follow the link [http://localhost/examples/get_history.php](http://localhost/examples/get_history.php)

### Get a merchant info
```php
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
```

**Example:**
Follow the link [http://localhost/examples/get_merchant_info.php](http://localhost/examples/get_merchant_info.php)

### Get cryptocurrency pair rates
```php
<?php
    require_once __DIR__ . "/../src/PaykassaCurrency.php";


    $pairs = [
        "BTC_USD",
        "USDT_ETH",
        "XRP_ADA",
    ];


    $res = \Paykassa\PaykassaCurrency::getCurrencyPairs($pairs);

    if ($res["error"]) {
        die($res["message"]);
    } else {
        $map_pairs = [];
        array_map(function ($pairs) use (&$map_pairs) {
            foreach ($pairs as $pair => $value) {
                $map_pairs[$pair] = $value;
            }
        }, $res["data"]);

        foreach ($map_pairs as $pair => $rate) {
            echo sprintf("Pairs - %s -> %s<br>", $pair, $rate);
        }

        $my_pair = "BTC_USD";
        echo sprintf("Request %s -> %s<br>", $my_pair, $map_pairs[$my_pair]);
    }
```

**Example:**
Follow the link [http://localhost/examples/get_currency_rates.php](http://localhost/examples/get_currency_rates.php)

### Get available currencies
```php
<?php
    require_once __DIR__ . "/../src/PaykassaCurrency.php";

    $res = \Paykassa\PaykassaCurrency::getAvailableCurrencies();

    if ($res["error"]) {
        die($res["message"]);
    } else {
        print_r($res["data"]);
    }
```

**Example:**
Follow the link [http://localhost/examples/get_available_currencies.php](http://localhost/examples/get_available_currencies.php)


## Contributing
If during your work with this wrapper you encounter a bug or have a suggestion to help improve it for others, you are welcome to open a Github issue on this repository and it will be reviewed by one of our development team members. The Paykassa.pro bug bounty does not cover this wrapper.

## License
MIT - see LICENSE