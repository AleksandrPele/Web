<?php
require_once 'vendor/autoload.php';

use Predis\Client;

class RedisExample
{
    private $client;

    public function __construct()
    {
        $this->client = new Client('tcp://redis:6379');
    }

    public function setValue($key, $value)
    {
        return $this->client->set($key, $value);
    }

    public function getValue($key)
    {
        return $this->client->get($key);
    }

    public function deleteValue($key)
    {
        return $this->client->del([$key]);
    }

    public function addNotification($userId, $type, $message)
    {
        $notification = [
            'id' => uniqid(),
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'created_at' => time(),
            'read' => false
        ];

        $key = "notifications:{$userId}";
        $this->client->lpush($key, json_encode($notification));
        $this->client->ltrim($key, 0, 99);
        $this->client->expire($key, 86400);

        return $notification;
    }

    public function getNotifications($userId, $limit = 20, $unreadOnly = false)
    {
        $key = "notifications:{$userId}";
        $notifications = $this->client->lrange($key, 0, $limit - 1);
        $result = array_map('json_decode', $notifications);

        if ($unreadOnly) {
            $result = array_filter($result, function($n) {
                return !$n->read;
            });
        }

        return $result;
    }

    public function markAsRead($userId, $notificationId = null)
    {
        $key = "notifications:{$userId}";
        $notifications = $this->client->lrange($key, 0, -1);

        if (empty($notifications)) {
            return false;
        }

        $this->client->del([$key]);

        foreach ($notifications as $notifJson) {
            $notif = json_decode($notifJson, true);

            if ($notificationId === null || $notif['id'] === $notificationId) {
                $notif['read'] = true;
            }

            $this->client->rpush($key, json_encode($notif));
        }

        $this->client->expire($key, 86400);
        return true;
    }

    public function getUnreadCount($userId)
    {
        $key = "notifications:{$userId}";
        $notifications = $this->client->lrange($key, 0, -1);
        $unread = 0;

        foreach ($notifications as $notifJson) {
            $notif = json_decode($notifJson);
            if (!$notif->read) {
                $unread++;
            }
        }

        return $unread;
    }

    public function incrementCounter($key)
    {
        return $this->client->incr($key);
    }

    public function getCounter($key)
    {
        return $this->client->get($key) ?? 0;
    }

    public function publish($channel, $message)
    {
        return $this->client->publish($channel, json_encode([
            'message' => $message,
            'timestamp' => time()
        ]));
    }
}