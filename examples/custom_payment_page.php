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


    $paykassa = new \Paykassa\PaykassaSCI(
        $secret_keys_and_config["merchant_id"],
        $secret_keys_and_config["merchant_password"],
        $secret_keys_and_config["config"]["test_mode"]
    );

    if ("GET" === $_SERVER['REQUEST_METHOD']) {
        $list = \Paykassa\PaykassaSCI::get_payment_systems("crypto");
?>
        <form action="" method="POST">
            <label>Select payment direction</label>
            <select name="pscur">
                <option value="">---</option>
                <?php foreach ($list as $item) { ?>
                    <?php foreach ($item["currency_list"] as $currency) { ?>
                        <option value="<?php echo htmlspecialchars(
                            sprintf("%s_%s", strtolower($item["system"]), strtolower($currency)),
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

    $res = $paykassa->create_address(
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