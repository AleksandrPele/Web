<?php
require 'vendor/autoload.php';
require 'QueueManager.php';

$q = new QueueManager();

echo "👷 Рабочий запущен (Kafka)...\n";

$q->consume(function($data) {
    echo "📥 Получено сообщение: " . json_encode($data) . "\n";
    sleep(2);
    file_put_contents('processed_kafka.log', json_encode($data) . PHP_EOL, FILE_APPEND);
    echo "✅ Обработано\n";
});