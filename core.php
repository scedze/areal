<?php
$host = 'localhost';
$db = 'test';
$user = 'postgres';
$pass = '0000';

try {
    $pdo = new PDO("pgsql:host = $host;dbname = $db", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("<div style = 'color:red; padding:20px; border:2px solid red;'> Ошибка" . $e->getMessage()."</div>");
}