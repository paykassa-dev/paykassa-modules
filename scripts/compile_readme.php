<?php
    define("TEMPLATE", __DIR__ . "/../templates/README.md.php");
    define("OUTPUT_FILE", __DIR__ . "/../README.md");

    ob_start();
    $content = require_once TEMPLATE;
    $res = file_put_contents(OUTPUT_FILE, ob_get_clean());
    echo $content !== false && false !== $res ? "OK" : "ERROR";