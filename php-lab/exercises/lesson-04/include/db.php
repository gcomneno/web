<?php

$dbHost = getenv("PHP4_DB_HOST") ?: "localhost";
$dbName = getenv("PHP4_DB_NAME") ?: "php4_shop_lab";
$dbUser = getenv("PHP4_DB_USER") ?: "php4_lab";
$dbPassword = getenv("PHP4_DB_PASSWORD");

if ($dbPassword === false) {
    throw new RuntimeException("PHP4_DB_PASSWORD is required");
}

$pdo = new PDO(
    "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
    $dbUser,
    $dbPassword,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
