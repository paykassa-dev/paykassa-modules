<?php

namespace Paykassa;
class PaykassaSCI
{
    private $version = "0.4";

    private static $system_settings = [
        "bitcoin" => [
            "type" => "crypto",
            "system_id" => 11,
            "system" => "BitCoin",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "bitcoin:",
            "display_name" => "Bitcoin",
            "currency_list" => [
                "BTC",
            ],
        ],
        "ethereum" => [
            "type" => "crypto",
            "system_id" => 12,
            "system" => "Ethereum",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "ethereum:",
            "display_name" => "Ethereum",
            "currency_list" => [
                "ETH",
            ],
        ],
        "litecoin" => [
            "type" => "crypto",
            "system_id" => 14,
            "system" => "LiteCoin",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "litecoin:",
            "display_name" => "Litecoin",
            "currency_list" => [
                "LTC",
            ],
        ],
        "dogecoin" => [
            "type" => "crypto",
            "system_id" => 15,
            "system" => "DogeCoin",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "dogecoin:",
            "display_name" => "Dogecoin",
            "currency_list" => [
                "DOGE",
            ],
        ],
        "dash" => [
            "type" => "crypto",
            "system_id" => 16,
            "system" => "Dash",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "dash:",
            "display_name" => "Dash",
            "currency_list" => [
                "DASH",
            ],
        ],
        "bitcoincash" => [
            "type" => "crypto",
            "system_id" => 18,
            "system" => "BitcoinCash",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "",
            "display_name" => "Bitcoin Cash",
            "currency_list" => [
                "BCH",
            ],
        ],
        "ethereumclassic" => [
            "type" => "crypto",
            "system_id" => 21,
            "system" => "EthereumClassic",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "ethereumclassic:",
            "display_name" => "Ethereum Classic",
            "currency_list" => [
                "ETC",
            ],
        ],
        "ripple" => [
            "type" => "crypto",
            "system_id" => 22,
            "system" => "Ripple",
            "tag" => true,
            "tag_title" => "tag",
            "qr_prefix" => "",
            "display_name" => "XRP",
            "currency_list" => [
                "XRP",
            ],
        ],
        "tron" => [
            "type" => "crypto",
            "system_id" => 27,
            "system" => "TRON",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "",
            "display_name" => "TRON",
            "currency_list" => [
                "TRX",
            ],
        ],
        "stellar" => [
            "type" => "crypto",
            "system_id" => 28,
            "system" => "Stellar",
            "tag" => true,
            "tag_title" => "memo",
            "qr_prefix" => "",
            "display_name" => "Stellar",
            "currency_list" => [
                "XLM",
            ],
        ],
        "binancecoin" => [
            "type" => "crypto",
            "system_id" => 29,
            "system" => "BinanceCoin",
            "tag" => false,
            "qr_prefix" => "",
            "display_name" => "BNB",
            "currency_list" => [
                "BNB",
            ],
        ],
        "tron_trc20" => [
            "type" => "crypto",
            "system_id" => 30,
            "system" => "TRON_TRC20",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "",
            "display_name" => "TRON(TRC20)",
            "currency_list" => [
                "USDT",
            ],
        ],
        "binancesmartchain_bep20" => [
            "type" => "crypto",
            "system_id" => 31,
            "system" => "BinanceSmartChain_BEP20",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "",
            "display_name" => "BNB Smart Chain(BEP20)",
            "currency_list" => [
                "USDT", "BUSD", "USDC", "ADA", "EOS", "BTC", "ETH", "DOGE", "SHIB",
            ],
        ],
        "ethereum_erc20" => [
            "type" => "crypto",
            "system_id" => 32,
            "system" => "Ethereum_ERC20",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "ethereum:",
            "display_name" => "Ethereum(ERC20)",
            "currency_list" => [
                "USDT", "BUSD", "USDC", "SHIB",
            ],
        ],
        "berty" => [
            "type" => "emoney",
            "system_id" => 7,
            "system" => "Berty",
            "tag" => false,
            "tag_title" => "",
            "qr_prefix" => "",
            "display_name" => "BertyCash",
            "currency_list" => [
                "USD", "RUB",
            ],
        ],
    ];


    public function
    __construct(
        string $sci_id,
        string $sci_key,
        bool $test = false
    )
    {
        $this->params = [];
        $this->params["sci_id"] = $sci_id;
        $this->params["sci_key"] = $sci_key;
        $this->params["test"] = $test;
        $this->params["domain"] = "";

        $this->url = "https://paykassa.app/sci/" . $this->version . "/index.php";

        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_FAILONERROR, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        if (!defined('CURL_HTTP_VERSION_2_0')) {
            define('CURL_HTTP_VERSION_2_0', 3);
        }

        curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Content-type: application/x-www-form-urlencoded',
        ]);
    }


    private function
    ok(
        string $message,
        array $data = []
    ): array
    {
        return [
            'error' => false,
            'message' => $message,
            'data' => $data,
        ];
    }

    private function
    err(
        string $message,
        array $data = []
    ): array
    {
        return [
            'error' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    private function
    requestPost(
        string $url,
        array $data = []
    ): array
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($this->curl, CURLOPT_POST, 1);

        $res = curl_exec($this->curl);
        if (false === $res) {
            return $this->err(
                curl_error($this->curl)
            );
        }
        $data = json_decode($res, true);
        if (null === $data || false === $data) {
            return $this->err(
                'Json parse error: ' . json_last_error_msg()
            );
        }

        if (!isset($data['error'], $data['message'])) {
            return $this->err('Bad format response');
        }

        return $data;
    }

    private function
    query(
        string $url,
        array $data = []
    ): array
    {
        return $this->requestPost($url, $data + $this->params);
    }

    final public function
    getSystemSettingsBySystemName(
        string $system_name
    ): array
    {
        $system_name = strtolower($system_name);
        if (isset(self::$system_settings[$system_name])) {
            return $this->ok("Ok", self::$system_settings[$system_name]);
        }
        return $this->err(
            sprintf(
                "System is not found. You can use next systems: %s",
                implode(
                    ", ",
                    array_map(
                        function (array $item): string {
                            return sprintf("%s: [ %s ]",
                                $item["system"],
                                implode(", ", $item["currency_list"])
                            );
                        },
                        self::$system_settings
                    )
                )
            )
        );
    }

    final public function
    getQrLink(
        array $response_data,
        string $amount=null
    ): array
    {
        $system_name = $response_data["system"];
        $currency = strtoupper($response_data["currency"]);
        $wallet = $response_data["wallet"];
        $tag = $response_data["tag"];

        if (empty($system_name) || empty($currency) || empty($wallet)) {
            return $this->err("Wrong params");
        }


        $res = $this->getSystemSettingsBySystemName($system_name);
        if ($res["error"]) {
            return $res;
        }

        $system_settings = $res["data"];

        if ("crypto" !== $system_settings["type"]) {
            return $this->err("You can use only crypto with the method");
        }

        if (false === in_array($currency, $system_settings["currency_list"], true)) {
            return $this->err(
                sprintf(
                    "Currency(%s) is not found. You can use next currencies: %s",
                    $currency,
                    implode(", ", $system_settings["currency_list"])
                )
            );
        }

        $query_params = [];

        $system_settings = $res["data"];
        if (true === $system_settings["tag"] && empty($tag)) {
            return $this->err("Wrong params");
        }
        if (true === $system_settings["tag"]) {
            $query_params =
                $query_params + [
                    "tag" => $tag,
                    "memo" => $tag,
                    "dt" => $tag,
                ];
        }

        if (null !== $amount) {
            $query_params = $query_params + [
               "amount" => $amount,
               "value" => $amount,
            ];
        }

        $qr_link = sprintf("%s%s%s%s",
            $system_settings["qr_prefix"], $wallet, count($query_params) ? "?" : "", http_build_query($query_params)
        );


        return $this->ok("OK", [
            "link" => $qr_link,
        ]);
    }

    final public function
    createOrder(
        string $amount,
        string $system_name,
        string $currency,
        string $order_id,
        string $comment = ""
    ): array
    {

        $currency = strtoupper($currency);

        $res = $this->getSystemSettingsBySystemName($system_name);
        if ($res["error"]) {
            return $res;
        }

        $system_settings = $res["data"];

        if (false === in_array($currency, $system_settings["currency_list"], true)) {
            return $this->err(
                sprintf(
                    "Currency(%s) is not found. You can use next currencies: %s",
                    $currency,
                    implode(", ", $system_settings["currency_list"])
                )
            );
        }

        return $this->query($this->url, [
                "func" => "sci_create_order",
            ] + [
                "amount" => $amount,
                "system" => $system_settings["system_id"],
                "currency" => $currency,
                "order_id" => $order_id,
                "comment" => $comment,
            ]);
    }


    final public function
    createAddress(
        string $system_name,
        string $currency,
        string $order_id,
        string $comment = ""
    ): array
    {

        $currency = strtoupper($currency);

        $res = $this->getSystemSettingsBySystemName($system_name);
        if ($res["error"]) {
            return $res;
        }

        $system_settings = $res["data"];

        if ("crypto" !== $system_settings["type"]) {
            return $this->err("You can use only crypto with the method");
        }

        if (false === in_array($currency, $system_settings["currency_list"], true)) {
            return $this->err(
                sprintf(
                    "Currency(%s) is not found. You can use next currencies: %s",
                    $currency,
                    implode(", ", $system_settings["currency_list"])
                )
            );
        }

        return $this->query($this->url, [
                "func" => "sci_create_order_get_data",
            ] + [
                "amount" => "1.00",
                "system" => $system_settings["system_id"],
                "currency" => $currency,
                "order_id" => $order_id,
                "comment" => $comment,
            ]);
    }

    final public function
    checkOrderIpn(
        string $private_hash
    ): array
    {
        return $this->query($this->url, [
                "func" => "sci_confirm_order",
            ] + [
                "private_hash" => $private_hash,]);
    }

    final public function
    checkTransactionIpn(
        string $private_hash
    ): array
    {
        return $this->query($this->url, [
                "func" => "sci_confirm_transaction_notification",
            ] + [
                "private_hash" => $private_hash,]);
    }

    final public static function
    getPaymentSystems(string $type=null): array {
        if (null === $type) {
            return self::$system_settings;
        }
        return array_filter(self::$system_settings, function ($item) use ($type) {
            return ($item["type"] === $type);
        });
    }
}
