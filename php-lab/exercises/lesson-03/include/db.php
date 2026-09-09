<?php

$dbHost = getenv("PHP3_DB_HOST") ?: "localhost";
$dbName = getenv("PHP3_DB_NAME") ?: "php3_shop_lab";
$dbUser = getenv("PHP3_DB_USER") ?: "php3_lab";
$dbPassword = getenv("PHP3_DB_PASSWORD");

if ($dbPassword === false) {
    throw new RuntimeException("PHP3_DB_PASSWORD is required");
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
