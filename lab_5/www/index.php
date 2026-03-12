<?php
require 'db.php';
require 'LoanApplication.php';

$loan = new LoanApplication($pdo);

if (isset($_GET['filter']) && $_GET['filter'] == 'large') {
    $applications = $loan->getLargeLoans();
} else {
    $applications = $loan->getAll();
}

$stats = $loan->getStats();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Заявки на кредит</title>
    <meta charset="utf-8">
</head>
<body>
    <h1>Заявки на кредит</h1>

    <!-- Статистика (штрафное задание) -->
    <div class="stats">
        <h3>Статистика:</h3>
        <p>Всего заявок: <?= $stats['total'] ?></p>
        <p>Средняя сумма: <?= $stats['avg'] ?> ₽</p>
    </div>

    <!-- Фильтр (штрафное задание) -->
    <div class="filter">
        <a href="index.php">Все заявки</a> |
        <a href="index.php?filter=large">Сумма > 100000 ₽</a>
    </div>

    <h2>Список заявок:</h2>
    <?php foreach($applications as $app): ?>
        <div class="app">
            <strong><?= htmlspecialchars($app['name']) ?></strong><br>
            Сумма: <?= $app['amount'] ?> ₽<br>
            Банк: <?= htmlspecialchars($app['bank']) ?><br>
            Срок: <?= $app['loan_term'] ?><br>
            Страховка: <?= $app['insurance'] ? 'Да' : 'Нет' ?><br>
            <span class="date">Дата: <?= $app['created_at'] ?></span>
        </div>
    <?php endforeach; ?>

    <p><a href="form.html">Новая заявка</a></p>
</body>
</html>