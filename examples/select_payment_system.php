<?php

    require_once __DIR__ . "/../src/PaykassaSCI.php";
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




    //$list = PaykassaSCI::get_payment_systems();
    $list = PaykassaAPI::get_payment_systems();

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