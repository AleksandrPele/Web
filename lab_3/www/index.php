<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная - Кредитные заявки</title>
</head>
<body>
    <h1>Система кредитных заявок</h1>

    <?php if (isset($_SESSION['errors'])): ?>
        <div style="color: red; border: 1px solid red; padding: 10px; margin: 10px 0;">
            <h3>Ошибки:</h3>
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['fullname'])): ?>
        <div style="border: 1px solid green; padding: 10px; margin: 10px 0;">
            <h3>Последняя заявка:</h3>
            <ul>
                <li><strong>ФИО:</strong> <?= htmlspecialchars($_SESSION['fullname']) ?></li>
                <li><strong>Сумма:</strong> <?= htmlspecialchars($_SESSION['amount']) ?> ₽</li>
                <li><strong>Банк:</strong> <?= htmlspecialchars($_SESSION['bank']) ?></li>
                <li><strong>Срок:</strong> <?= htmlspecialchars($_SESSION['term']) ?></li>
                <li><strong>Страховка:</strong> <?= htmlspecialchars($_SESSION['insurance']) ?></li>
            </ul>
        </div>
    <?php else: ?>
        <p>Заявок пока нет</p>
    <?php endif; ?>

    <hr>

    <nav>
        <a href="form.html">Новая заявка</a> |
        <a href="view.php">Все заявки</a>
    </nav>
</body>
</html>