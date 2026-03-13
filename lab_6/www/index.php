<?php
require_once 'vendor/autoload.php';
require_once 'RedisExample.php';
require_once 'db.php';

echo "<h1>Лабораторная работа №6: Redis (Уведомления)</h1>";

$redis = new RedisExample();

echo "<h2>Базовые операции</h2>";
$redis->setValue('test_key', 'Hello Redis!');
echo "getValue('test_key'): " . $redis->getValue('test_key') . "<br>";

echo "<h2>Система уведомлений</h2>";

$redis->addNotification(1, 'warning', 'Ваш пароль скоро истечет');
$redis->addNotification(1, 'success', 'Ваш заказ подтвержден');
$redis->addNotification(1, 'info', 'Новое сообщение в чате');

echo "Добавлено 4 уведомления для пользователя 1<br>";

$notifications = $redis->getNotifications(1);
echo "<h3>Все уведомления:</h3>";
foreach ($notifications as $n) {
    echo "ID: {$n->id}, Type: {$n->type}, Message: {$n->message}, Read: " . ($n->read ? 'да' : 'нет') . "<br>";
}

echo "<br>Непрочитанных уведомлений: " . $redis->getUnreadCount(1) . "<br>";

$firstNotifId = $notifications[0]->id;
$redis->markAsRead(1, $firstNotifId);
echo "Уведомление {$firstNotifId} отмечено как прочитанное<br>";

echo "Непрочитанных после отметки: " . $redis->getUnreadCount(1) . "<br>";

$redis->addNotification(2, 'alert', 'Важная системная информация');
echo "<br>Добавлено уведомление для пользователя 2<br>";

echo "<h2>Счетчики</h2>";
$redis->incrementCounter('page_views');
$redis->incrementCounter('page_views');
echo "Просмотров страницы: " . $redis->getCounter('page_views') . "<br>";

echo "<h2>Публикация события</h2>";
$redis->publish('notifications', 'Тестовое уведомление');
echo "Событие опубликовано в канал 'notifications'<br>";

echo "<h2>Очистка</h2>";
$redis->deleteValue('test_key');
echo "test_key удален<br>";
?>