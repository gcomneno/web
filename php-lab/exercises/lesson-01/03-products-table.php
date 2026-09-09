<?php

declare(strict_types=1);

$products = [
    [
        'name' => 'Notebook',
        'price' => 12.50,
        'quantity' => 3,
    ],
    [
        'name' => 'Keyboard',
        'price' => 49.90,
        'quantity' => 2,
    ],
    [
        'name' => 'Mouse',
        'price' => 19.90,
        'quantity' => 4,
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP 1 - Products</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['name'] ?></td>
            <td><?= number_format($product['price'], 2, '.', '') ?></td>
            <td><?= $product['quantity'] ?></td>
            <td><?= number_format(
                $product['price'] * $product['quantity'],
                2,
                '.',
                ''
            ) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
