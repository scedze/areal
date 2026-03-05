<?php require_once 'core.php'; ?>
<!DOCTYPE html>
<html lang = "ru">
<head>
    <meta charset = "UTF-8">
    <title>Учет сотрудников</title>
    <link rel="sttylesheet" href="style.css">
</head>

<body>

    <header style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Сотрудник</h1>
        <a href = "?action=add" class="btn-primary">Добавить сотрудника</a>
        </header>
    <form class="controls" method = "GET" action = "index.php">
        <input type="text" name = "search" placeholder="Поиск" value="<?= htmlspecialchars($search) ?>">

        <select name = "dept_id" id="filter_dept">
            <option value = "">Все отделы</option>
            <?php foreach($all_depts as $d): ?>
                <option value = "<?= $d['id'] ?>" <?= ($dept_f == $d['id']) ?'selected':''?>>
                    <?= htmlspecialchars($d['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="pos_id" id="filter_pos">
            <option value ="">Все должности</option>
            <?php foreach($all_positions as $p): ?>
                <option value ="<?= $p['id'] ?>"
                        data-dept = "<?= $p['id_otdela'] ?>"
                        <?= ($pos_f == $p['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type = "submit" class = "btn-primary">Применить</button>
    </form>
</body>
</html>