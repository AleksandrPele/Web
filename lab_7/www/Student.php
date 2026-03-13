<?php

class Student {
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->initTable();
    }

    private function initTable() {
        $sql = "CREATE TABLE IF NOT EXISTS students (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            phone TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            processed_at DATETIME
        )";
        $this->db->exec($sql);
    }

    public function save($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO students (name, email, phone, processed_at)
             VALUES (:name, :email, :phone, :processed_at)"
        );

        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':email', $data['email'] ?? '');
        $stmt->bindValue(':phone', $data['phone'] ?? '');
        $stmt->bindValue(':processed_at', date('Y-m-d H:i:s'));

        return $stmt->execute();
    }

    public function getAll() {
        $result = $this->db->query("SELECT * FROM students ORDER BY created_at DESC");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}