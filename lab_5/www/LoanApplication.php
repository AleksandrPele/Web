<?php
class LoanApplication {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->createTable();
    }

    private function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS loan_applications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            bank VARCHAR(100) NOT NULL,
            insurance TINYINT(1) DEFAULT 0,
            loan_term VARCHAR(20) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    public function add($name, $amount, $bank, $insurance, $loan_term) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO loan_applications (name, amount, bank, insurance, loan_term)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $amount, $bank, $insurance, $loan_term]);
    }

    public function getAll($filter = null) {
        $sql = "SELECT * FROM loan_applications";

        if ($filter === 'high_amount') {
            $sql .= " WHERE amount > 100000";
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getStats() {
        $stats = [];

        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM loan_applications");
        $stats['total'] = $stmt->fetch()['total'];

        $stmt = $this->pdo->query("SELECT COUNT(*) as insured FROM loan_applications WHERE insurance = 1");
        $stats['insured'] = $stmt->fetch()['insured'];

        $stmt = $this->pdo->query("SELECT AVG(amount) as avg_amount FROM loan_applications");
        $stats['avg_amount'] = round($stmt->fetch()['avg_amount'] ?? 0, 2);

        $stmt = $this->pdo->query("SELECT SUM(amount) as total_amount FROM loan_applications");
        $stats['total_amount'] = round($stmt->fetch()['total_amount'] ?? 0, 2);

        return $stats;
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM loan_applications WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $name, $amount, $bank, $insurance, $loan_term) {
        $stmt = $this->pdo->prepare(
            "UPDATE loan_applications
             SET name=?, amount=?, bank=?, insurance=?, loan_term=?
             WHERE id=?"
        );
        $stmt->execute([$name, $amount, $bank, $insurance, $loan_term, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM loan_applications WHERE id=?");
        $stmt->execute([$id]);
    }
}
?>