<?php
class UserInfo {
    public static function getInfo(): array {
        return [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Не определен',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Не определен',
            'time' => date('Y-m-d H:i:s'),
            'browser_language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Не определен',
            'protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'Не определен',
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'Не определен'
        ];
    }

    public static function getBrowserInfo(): string {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (strpos($ua, 'Chrome') !== false) return 'Google Chrome';
        if (strpos($ua, 'Firefox') !== false) return 'Mozilla Firefox';
        if (strpos($ua, 'Safari') !== false) return 'Apple Safari';
        if (strpos($ua, 'Edge') !== false) return 'Microsoft Edge';
        if (strpos($ua, 'Opera') !== false || strpos($ua, 'OPR') !== false) return 'Opera';
        return 'Неизвестный браузер';
    }
}