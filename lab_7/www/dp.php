<?php

function getDB() {
    static $db = null;
    if ($db === null) {
        $dbFile = __DIR__ . '/students.db';
        $db = new PDO('sqlite:' . $dbFile);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $db;
}