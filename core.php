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

if($_SERVER["REQUEST_METHOD"] === 'POST' && isset( $_POST['action'])) {
    if($_POST['action'] === 'create') {
        $email_array = '{' . $_POST['email'] . '}';
        $sql = "INSERT INTO sotrudnik (familiya, imya, otchestvo, data_rojdeniya, seriya_pasport, nomer_pasport, nomer_telefona, email, adres, id_otdela, id_doljnosti, zarplata, data_priema, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, true)";
        $pdo -> prepare($sql) -> execute([
            $_POST['familiya'], $_POST['imya'], $_POST['otchestvo'], $_POST['data_rojdeniya'],
            $_POST['seriya'], $_POST['nomer'], $_POST['nomer_telefona'], $email_array,
            $_POST['adres'] ?? '', $_POST['id_otdela'], $_POST['id_doljnosti'], $_POST['zarplata'], $_POST['data_priema'],

        ]);
    }
} 