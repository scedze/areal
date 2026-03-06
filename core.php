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
            $_POST['seriya_pasport'], $_POST['nomer_pasport'], $_POST['nomer_telefona'], $email_array,
            $_POST['adres'] ?? '', $_POST['id_otdela'], $_POST['id_doljnosti'], $_POST['zarplata'], $_POST['data_priema'],

        ]);
    }
    if ($_POST['action'] === 'update'){
        $email_array = '{'. $_POST['email'] . '}';
        $sql = "UPDATE sotrudnik SET
                    familiya = ?, imya = ?, otchestvo = ?, data_rojdeniya = ?,
                    seriya_pasport = ?, nomer_pasport = ?, nomer_telefona = ?,
                    email = ?, adres = ?, id_otdela = ?, id_doljnosti = ?,
                    zarplata = ?, data_priema = ?
                WHERE id_sotrudnik = ?";
        $pdo -> prepare( $sql) -> execute([
            $_POST['familiya'], $_POST['imya'], $_POST['otchestvo'], $_POST['data_rojdeniya'],
            $_POST['seriya_pasport'], $_POST['nomer_pasport'], $_POST['nomer_telefona'],
            $email_array, $_POST['adres'], $_POST['id_otdela'],
            $_POST['id_doljnosti'], $_POST['zarplata'], $_POST['data_priema'],
            $_POST['id_sotrudnik']
        ]);

    } 
    if ($_POST['action'] === 'fire'){
        $stmt = $pdo -> prepare("UPDATE sotrudnik SET status = false, data_uvolneniya = CURRENT_DATE WHERE id_sotrudnik = ?");
        $stmt -> execute([$_POST['ID']]);
    }

    header("Location: index.php");
    exit;
} 
$all_depts = $pdo -> query("SELECT id_otdela as id, nazvanie_otdela as name FROM otdel ORDER BY name")->fetchAll();
$all_positions = $pdo -> query("SELECT id_doljnosti as id, nazvanie_doljnosti as name, id_otdela FROM doljnost ORDER BY name")->fetchAll();
$search = $_GET["search"] ?? '';
$dept_f = $_GET['dept_id'] ?? '';
$pos_f = $_GET['pos_id'] ?? '';
$sql = "SELECT s.*, o.nazvanie_otdela as dept_name, d.nazvanie_doljnosti as pos_name
        FROM sotrudnik s
        LEFT JOIN otdel o ON s.id_otdela = o.id_otdela
        LEFT JOIN doljnost d ON s.id_doljnosti = d.id_doljnosti
        WHERE (s.familiya ILIKE ? OR s.imya ILIKE ?)";
$params = ["%$search%", "%$search%"];
if (!empty($dept_f)) {
    $sql .= " AND s.id_otdela = ?";
    $params[] = $dept_f;
}
if (!empty($pos_f)) {
    $sql .= " AND s.id_doljnosti = ?";
    $params[] = $pos_f;
}
$sql .= "  ORDER BY s.status DESC, s.familiya ASC";
$stmt = $pdo -> prepare($sql);
$stmt -> execute($params);
$employees = $stmt -> fetchAll();

$current_emp = null;
if (isset($_GET['view_id'])) {
    $stmt = $pdo -> prepare("SELECT s.*, o.nazvanie_otdela, d.nazvanie_doljnosti FROM sotrudnik s LEFT JOIN otdel o ON s.id_otdela = o.id_otdela LEFT JOIN doljnost d ON s.id_doljnosti = d.id_doljnosti WHERE s.id_sotrudnik = ?");
    $stmt -> execute([$_GET['view_id']]);
    $current_emp = $stmt -> fetch();
}

$edit_emp = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo -> prepare("SELECT * FROM sotrudnik WHERE id_sotrudnik = ? AND status = true");
    $stmt -> execute([$_GET['edit_id']]);
    $edit_emp = $stmt -> fetch();

    if (!$edit_emp && isset($_GET['edit_id'])) {
        header("Location: index.php");
        exit;
    }
}