<?php
session_start();

require_once 'ApiClient.php';
require_once 'UserInfo.php';

$userInfo = UserInfo::getInfo();
$browser = UserInfo::getBrowserInfo();

$api = new ApiClient();

$url = 'https://www.cbr-xml-daily.ru/daily_json.js';

$apiData = $api->request($url);

$_SESSION['api_data'] = $apiData;

setcookie("last_visit", date('Y-m-d H:i:s'), time() + 3600, "/");

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Курсы валют ЦБ РФ</title>
</head>
<body>
    <h1>Курсы валют от Центрального банка РФ</h1>

    <div class="info-box">
        <h3>Информация о загрузке:</h3>
        <?php if (isset($_SESSION['api_data']['Date'])): ?>
            <p>Дата курсов: <?php echo htmlspecialchars($_SESSION['api_data']['Date']); ?></p>
            <p>Предыдущая дата: <?php echo htmlspecialchars($_SESSION['api_data']['PreviousDate']); ?></p>
        <?php endif; ?>

        <?php if (isset($_COOKIE['last_visit'])): ?>
            <p>Последнее посещение: <?php echo htmlspecialchars($_COOKIE['last_visit']); ?></p>
        <?php endif; ?>
    </div>

    <div class="info-box" style="background-color: #fff3cd; border-left-color: #ffc107;">
        <h3>Информация о пользователе:</h3>
        <p><strong>IP-адрес:</strong> <?php echo htmlspecialchars($userInfo['ip']); ?></p>
        <p><strong>Браузер:</strong> <?php echo htmlspecialchars($browser); ?></p>
        <p><strong>User Agent:</strong> <?php echo htmlspecialchars($userInfo['user_agent']); ?></p>
        <p><strong>Время запроса:</strong> <?php echo htmlspecialchars($userInfo['time']); ?></p>
        <p><strong>Язык браузера:</strong> <?php echo htmlspecialchars($userInfo['browser_language']); ?></p>
    </div>

    <h2>Курсы валют</h2>

    <?php if (isset($_SESSION['api_data']['Valute']) && !empty($_SESSION['api_data']['Valute'])): ?>
        <table class="currency-table">
            <thead>
                <tr>
                    <th>Код</th>
                    <th>Валюта</th>
                    <th>Номинал</th>
                    <th>Курс (руб.)</th>
                    <th>Изменение</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['api_data']['Valute'] as $code => $currency): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($currency['CharCode']); ?></strong></td>
                        <td><?php echo htmlspecialchars($currency['Name']); ?></td>
                        <td><?php echo htmlspecialchars($currency['Nominal']); ?></td>
                        <td><?php echo number_format($currency['Value'], 4, '.', ' '); ?></td>
                        <td style="color: <?php echo ($currency['Value'] - $currency['Previous']) > 0 ? 'green' : 'red'; ?>">
                            <?php
                            $change = $currency['Value'] - $currency['Previous'];
                            echo ($change > 0 ? '+' : '') . number_format($change, 4, '.', ' ');
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Ошибка получения данных: <?php echo $_SESSION['api_data']['error'] ?? 'Неизвестная ошибка'; ?></p>
    <?php endif; ?>

</body>
</html>