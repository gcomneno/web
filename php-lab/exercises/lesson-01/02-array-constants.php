<?php

declare(strict_types=1);

$product = [
    'name' => 'Keyboard',
    'price' => 49.90,
    'quantity' => 2,
];

$product['total'] = $product['price'] * $product['quantity'];

define('SHOP_NAME', 'PHP Lab Shop');
const CURRENCY = 'EUR';

echo 'shop: ' . SHOP_NAME . "\n";
echo 'product: ' . $product['name'] . "\n";
echo 'price: ' . number_format($product['price'], 2, '.', '') . "\n";
echo 'quantity: ' . $product['quantity'] . "\n";
echo 'total: ' . number_format($product['total'], 2, '.', '') . "\n";
echo 'currency: ' . CURRENCY . "\n";
echo 'php-version: ' . PHP_VERSION . "\n";
echo 'directory: ' . __DIR__ . "\n";
