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
    <table>
        <thead>
            <tr>
                <th>ФИО</th>
                <th>Отдел / Должность</th>
                <th>Статус</th>
                <th style="text-align: right">Действие</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($employees as $emp): ?>
        <tr class="<?=  !$emp['status'] ? 'fired' : '' ?>">
            <td><?= htmlspecialchars($emp['familiya'] . ' ' . $emp['imya']) ?></td>
            <td><?= htmlspecialchars($emp['dept_name'] . ' / ' . $emp['pos_name']) ?></td>
            <td>
                <?php if ($emp['status']): ?>
                    <span style="color: pink">Работает</span>
                <?php else: ?>
                    <span style="color:darkgray; font-weight: bold;">УВОЛЕН</span>
                <?php endif; ?>
            </td>
            <td style="text-align: right">
                <a href="?view_id=<?= $emp['id_sotrudnik'] ?>" class="btn-more">Подробнее</a>
                <?php if ($emp['status']): ?>
                    <a href="?edit_id= <?= $emp['id_sotrudnik'] ?>" class="btn-more" style="border-color: beige; color: beige">Редактировать</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
    
</body>
</html>