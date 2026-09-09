<?php

declare(strict_types=1);

echo "numbers:\n";

for ($number = 1; $number <= 20; $number++) {
    if ($number % 3 === 0) {
        continue;
    }

    echo $number . "\n";
}

$cities = [
    'Lucca' => [
        'region' => 'Tuscany',
        'population' => 90000,
    ],
    'Pisa' => [
        'region' => 'Tuscany',
        'population' => 91000,
    ],
    'Florence' => [
        'region' => 'Tuscany',
        'population' => 360000,
    ],
];

echo "cities:\n";

foreach ($cities as $city => $data) {
    echo $city
        . ' | '
        . $data['region']
        . ' | '
        . $data['population']
        . "\n";
}

echo "city-names:\n";

foreach (array_keys($cities) as $city) {
    echo $city . "\n";
}
