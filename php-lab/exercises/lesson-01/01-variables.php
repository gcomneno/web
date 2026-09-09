<?php

declare(strict_types=1);

$first = 10;
$second = 20;

echo "before: first=$first second=$second\n";

$temporary = $first;
$first = $second;
$second = $temporary;

echo "after: first=$first second=$second\n";

$name = 'Notebook';
$price = 12.50;
$quantity = 3;
$total = $price * $quantity;

echo "product: $name\n";
echo 'unit price: ' . number_format($price, 2, '.', '') . "\n";
echo "quantity: $quantity\n";
echo 'total: ' . number_format($total, 2, '.', '') . "\n";
