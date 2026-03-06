<?php require_once 'core.php'; ?>
<!DOCTYPE html>
<html lang = "ru">
<head>
    <meta charset = "UTF-8">
    <title>Учет сотрудников</title>
    <style>
        :root { --main-pink: #ff85a2; --bg-pink: #fff0f3; --dark: #4a3a3d;}
        body {font-family: 'Segoe UI', sans-serif; background: #fff; color: var(--dark); margin:0; padding: 40px;}
        h1 { color: var(--main-pink); font-weight: 300; letter-spacing: 2px;}
        
        .controls { display:flex; gap: 15px; background: var(--bg-pink); padding: 20px; border-radius: 15px; margin-bottom: 30px;}
        input, select { padding: 10px; border: 1px solid #ffccd5; border-radius: 8px; outline: none;}
        
        .btn-primary { background: var(--main-pink); color: white; border: none; padding: 10px 25px; border-radius: 20px; cursor: pointer; text-decoration: none; display:inline-block;}
        .btn-more { border: 1px solid var(--main-pink); color: var(--main-pink); padding: 5px 15px; border-radius: 15px; text-decoration: none; font-size: 14px;}
        
        table{ width: 100%; border-collapse: collapse; margin-top: 20px;}
        th { text-align: left; border-bottom: 2 px solid var(--bg-pink);padding: 15px; color: var(--main-pink);}
        td { padding: 15px;  border-bottom: 1 px solid var(--bg-pink);}
        .fired { background: #f9f9f9; opacity: 0.6;}
        .fired td { text-decoration: line-through;}
        
        .modal-overlay {position: fixed; top:0; left:0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: none; justify-content: center; align-items: center;}
        .modal-overlay.active {display: flex;}
        .modal-card { background: white; padding: 30px; border-radius: 20px; width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);}
        .modal-card input {width: 100%; margin-bottom: 10px; box-sizing: border-box;}
    </style>
</head>
<body>

    <header style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Сотрудники</h1>
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
                    <a href="?edit_id= <?= $emp['id_sotrudnik'] ?>" class="btn-more" style="border-color: #4a3a3d; color: #4a3a3d">✎</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
    <?php if ($current_emp): ?>
    <div class="modal-overlay active">
        <div class="modal-card">
            <h2><?= htmlspecialchars($current_emp['familiya'] . ' ' . $current_emp['imya']) ?></h2>
            <p><strong>Паспорт:</strong> <?= htmlspecialchars($current_emp['seriya_pasport'] . ' ' . $current_emp['nomer_pasport']) ?></p>
            <p><strong>Почта:</strong><?= str_replace(['{','}'], '', $current_emp['email']) ?></p>
            <p><strong>Телефон:</strong><?= $current_emp['nomer_telefona'] ?></p>
            <p><strong>Зарплата:</strong><?= number_format($current_emp['zarplata'], 0, '.', ' ') ?>руб.</p>
            <p><strong>Отдел:</strong><?= htmlspecialchars($current_emp['nazvanie_otdela']) ?></p>
            <p><strong>Должность:</strong><?= htmlspecialchars($current_emp['nazvanie_doljnosti']) ?></p>
            <p><strong>Адрес:</strong><?= htmlspecialchars($current_emp['adres'] ?: 'Не указан') ?></p>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <a href="index.php" class="btn-primary" style="background: #ccc;">Закрыть</a>
                <?php if ($current_emp['status']): ?>
                <form method="POST">
                    <input type="hidden" name="action" value="fire">
                    <input type="hidden" name="id" value="<?= $current_emp['id_sotrudnik'] ?>">
                    <button type="submit" class="btn-primary" style="background: crimson;">Уволить</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if (isset($_GET['action']) && $_GET['action'] === 'add'): ?>
        <div class="modal-overlay active">
            <div class="modal-card">
                <h2>Новый сотрудник</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="create">
                    <input type="text" name="familiya" placeholder="Фамилия" required>
                    <input type="text" name="imya" placeholder="Имя" required>
                    <input type="text" name="otchestvo" placeholder="Отчество">
                    <input type="text" name="seriya_pasport" placeholder="Серия паспорта" required>
                    <input type="text" name="nomer_pasport" placeholder="Номер паспорта" required>
                    <select name="id_otdela" id="dept_select" required style="width: 100%; margin-bottom: 10px">
                        <option value="">-- Выберите отдел --</option>
                        <?php foreach($all_depts as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="id_doljnosti" id="pos_select" required style="width: 100%; margin-bottom: 10px">
                        <option value="">-- Сначала выберите отдел --</option>
                        <?php foreach($all_positions as $p): ?>
                            <option value="<?= $p['id'] ?>" data-dept="<?= $p['id_otdela'] ?>">
                                <?= htmlspecialchars($p['name']) ?> 
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" name="data_rojdeniya" required title="Дата рождения">
                    <input type="text" name="nomer_telefona" placeholder="Телефон" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="text" name="adres" placeholder="Адрес проживания">
                    <input type="number" name="zarplata" placeholder="Зарплата" required>
                    <input type="date" name="data_priema" required title="Дата приема">
                    <button type="submit" class="btn-primary">Сохранить</button>
                    <a href="index.php" style="margin-left: 15px; color: #999;">Отмена</a>
                </form>
            </div>
        </div>
        <?php endif; ?>
    <?php if ($edit_emp): ?>
        <div class="modal-overlay active">
            <div class="modal-card">
                <h2>Редактировать данные</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id_sotrudnik" value="<?= $edit_emp['id_sotrudnik'] ?>">
                    <input type="text" name="familiya" value="<?= htmlspecialchars($edit_emp['familiya']) ?>" required>
                    <input type="text" name="imya" value="<?= htmlspecialchars($edit_emp['imya']) ?>" required>
                    <input type="text" name="otchestvo" value="<?= htmlspecialchars($edit_emp['otchestvo']) ?>" >
                    <input type="text" name="seriya_pasport" value="<?= htmlspecialchars($edit_emp['seriya_pasport']) ?>" placeholder="Серия" required>
                    <input type="text" name="nomer_pasport" value="<?= htmlspecialchars($edit_emp['nomer_pasport']) ?>" placeholder="Номер" required>
                    <select name="id_otdela" id="dept_select_edit" required style="width: 100%; margin-bottom: 10px;">
                        <?php foreach($all_depts as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= $edit_emp['id_otdela'] == $d['id'] ? 'selected' : ''?>>
                                <?= htmlspecialchars($d['name']) ?> 
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="id_doljnosti" id="pos_select_edit" required style="width: 100%; margin-bottom: 10px;">
                        <?php foreach($all_positions as $p): ?>
                            <option value="<?= $p['id'] ?>" data-dept="<?= $p['id_otdela'] ?>" <?= $edit_emp['id_doljnosti'] == $p['id'] ? 'selected' : ''?>>
                                <?= htmlspecialchars($p['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="date" name="data_rojdeniya" value="<?= $edit_emp['data_rojdeniya'] ?>" required>
                    <input type="text" name="nomer_telefona" value="<?= $edit_emp['nomer_telefona'] ?>" required>
                    <input type="email" name="email" value="<?= str_replace(['{','}'], '', $edit_emp['email']) ?>" required>
                    <input type="number" name="zarplata" value="<?= $edit_emp['zarplata'] ?>" required>
                    <input type="date" name="data_priema" value="<?= $edit_emp['data_priema'] ?>" required>
                    <div style="margin-top: 10px;">
                        <button type="submit" class="btn-primary">Обновить</button>
                        <a href="index.php" style="margin-left: 15px; color: #999;">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
<script>
    document.addEventListener('input', function (e){
        if (e.target.name === 'nomer_telefona') {
            let matrix = "+7 (___) ___-__-__",
                i = 0,
                def = matrix.replace(/\D/g, ""),
                val = e.target.value.replace(/\D/g, "");
            if (def.length >= val.length) val = def;
            e.target.value = matrix.replace(/./g, function(a) {
                return /[_\d]/.test(a) && i < val.length ? val.charAt(i++) : i >= val.length ? "" : a
            });
        }
        if (e.target.name === 'seriya_pasport') {
            let val = e.target.value.replace(/\D/g, '');
            e.target.value = val.substring(0,4);
        }
        if (e.target.name === 'nomer_pasport') {
            let val = e.target.value.replace(/\D/g, '');
            e.target.value = val.substring(0,6);
        }
    });
</script>
<script>
    function setupLinkedSelects(deptId, posId) {
        const dept = document.getElementById(deptId);
        const pos = document.getElementById(posId);
        if (!dept || !pos) return;
        const allOptions =Array.from(pos.options);
        dept.addEventListener('change', function() {
            const val = this.value;
            pos.value = "";
            allOptions.forEach(opt => {
                if (opt.value === "") return;
                const deptIdOfPos = opt.getAttribute('data-dept');
                if (val === "" || deptIdOfPos === val) {
                    opt.style.display = 'block';
                    opt. disabled = false;
                } else {
                    opt.style.display = 'none';
                    opt. disabled = true;
                }
            });
        });
    }
    setupLinkedSelects('filter_dept', 'filter_pos');
    setupLinkedSelects('dept_select', 'pos_select');
    setupLinkedSelects('dept_select_edit', 'pos_select_edit');
</script>
</body>
</html>