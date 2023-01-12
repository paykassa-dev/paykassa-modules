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
