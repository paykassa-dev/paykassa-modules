# Paykassa.pro PHP SCI/API Wrapper(Official).

This is an official module developed by Paykassa.pro for easy integration into PHP-based applications.
Source code of the class files is in the ./src directory. The source code for the examples can be found at ./examples.


## Requirements
It's recommended to use a newer version of PHP. This library was written in a PHP v7.2.34 environment + php-curl modules.

A Paykassa.pro account with **Merchant ID, Merchant Password, API ID, API Password**. You can get the credentials at the pages: [Add merchant](https://paykassa.pro/en/user/shops/add_shop_new/), [Add API](https://paykassa.pro/en/user/api/add_api/).

## Installation

Package available on [Composer](https://packagist.org/packages/paykassa-dev/paykassa).

If you're using Composer to manage dependencies, you can use

```bash
$ composer require paykassa-dev/paykassa
```

## Usage
### Custom payment page
```php
<?php echo file_get_contents(__DIR__ . "/../examples/custom_payment_page.php"); ?>

```


### Get a payment address and a QR-code for him.
```php
<?php echo file_get_contents(__DIR__ . "/../examples/create_address.php"); ?>

```


### Check an IPN of a transaction.
```php
<?php echo file_get_contents(__DIR__ . "/../examples/processing_ipn_for_transaction.php"); ?>

```

### Get a payment link(create an order).
```php
<?php echo file_get_contents(__DIR__ . "/../examples/create_payment_link.php"); ?>

```

### Check an IPN of an order.
```php
<?php echo file_get_contents(__DIR__ . "/../examples/processing_ipn_for_order.php"); ?>

```

### Send a money
```php
<?php echo file_get_contents(__DIR__ . "/../examples/send_money.php"); ?>

```


### Get a merchant balance

```php
<?php echo file_get_contents(__DIR__ . "/../examples/get_merchant_balance.php"); ?>

```

### Get a merchant history

```php
<?php echo file_get_contents(__DIR__ . "/../examples/get_history.php"); ?>

```

### Get a merchant info

```php
<?php echo file_get_contents(__DIR__ . "/../examples/get_merchant_info.php"); ?>

```



### Get cryptocurrency pair rates

```php
<?php echo file_get_contents(__DIR__ . "/../examples/get_currency_rates.php"); ?>

```


### Get Available Currencies

```php
<?php echo file_get_contents(__DIR__ . "/../examples/get_available_currencies.php"); ?>

```

## Contributing
If during your work with this wrapper you encounter a bug or have a suggestion to help improve it for others, you are welcome to open a Github issue on this repository and it will be reviewed by one of our development team members. The Paykassa.pro bug bounty does not cover this wrapper.

## License
MIT - see LICENSE