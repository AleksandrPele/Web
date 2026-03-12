<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все кредитные заявки</title>
</head>
<body>
    <h1>Все сохранённые кредитные заявки</h1>

    <?php
    $file = __DIR__ . '/data.txt';

    if (file_exists($file) && filesize($file) > 0):
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    ?>
        <table>
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>ФИО</th>
                    <th>Сумма</th>
                    <th>Банк</th>
                    <th>Срок</th>
                    <th>Страховка</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lines as $line): ?>
                    <?php $data = explode(';', $line); ?>
                    <tr>
                        <td><?= htmlspecialchars($data[0] ?? '') ?></td>
                        <td><?= htmlspecialchars($data[1] ?? '') ?></td>
                        <td><?= htmlspecialchars($data[2] ?? '') ?> ₽</td>
                        <td><?= htmlspecialchars($data[3] ?? '') ?></td>
                        <td><?= htmlspecialchars($data[4] ?? '') ?></td>
                        <td><?= htmlspecialchars($data[5] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Заявок пока нет.</p>
    <?php endif; ?>

    <hr>

    <nav>
        <a href="form.html">Новая заявка</a> |
        <a href="index.php">На главную</a>
    </nav>
</body>
</html>