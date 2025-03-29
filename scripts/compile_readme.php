<?php

    use Paykassa\PaykassaAPI;

    define("TEMPLATE", __DIR__ . "/../templates/README.md.php");
    define("OUTPUT_FILE", __DIR__ . "/../README.md");

    require_once __DIR__ . "/../src/PaykassaAPI.php";

    $systems = PaykassaAPI::getSystemSettings();

    ob_start();
        echo "     /*";
        echo PHP_EOL;
        foreach ($systems as $item) {
            echo sprintf("     * %s [ %s ]", $item["system"], implode(", ", $item["currency_list"]));
            echo PHP_EOL;
        }
        echo "     */";
    $systems_info = ob_get_clean();

    ob_start();
    $res_content = require_once TEMPLATE;
    $content = ob_get_clean();
    $content = preg_replace([
        '#^(.+?)include_once(.+?)config/config-example(.+?)$\n#m',
        '#^(.+?)require_once(.+?)vendor/autoload(.+?)$\n#m'
    ], "", $content);

    $content = str_replace(
        '/* ### SYSTEMS_INFO ### */',
        $systems_info,
        $content);

    $res = file_put_contents(OUTPUT_FILE, $content);
    echo $res_content !== false && false !== $res ? "OK" : "ERROR";