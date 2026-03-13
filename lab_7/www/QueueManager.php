<?php
use Kafka\Producer;
use Kafka\ProducerConfig;
use Kafka\Consumer;
use Kafka\ConsumerConfig;

class QueueManager {
    private $topic = 'lab7_topic';

    public function publish($data) {
        $config = ProducerConfig::getInstance();
        $config->setMetadataBrokerList('kafka:9092');

        $producer = new Producer(function() use ($data) {
            return [[
                'topic' => $this->topic,
                'value' => json_encode($data),
                'key' => '',
            ]];
        });

        $producer->send(true);
    }

    public function consume(callable $callback) {
        $config = ConsumerConfig::getInstance();
        $config->setMetadataBrokerList('kafka:9092');
        $config->setGroupId('lab7_group');
        $config->setTopics([$this->topic]);
        $config->setOffsetReset('earliest');

        $consumer = new Consumer();
        $consumer->start(function($topic, $part, $message) use ($callback) {
            $data = json_decode($message['message']['value'], true);
            $callback($data);
        });
    }
}