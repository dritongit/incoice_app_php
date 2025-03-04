<?php
require_once __DIR__ . '/../core/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($email, $password, $role, $pin) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $hashedPin = password_hash($pin, PASSWORD_BCRYPT);
        $stmt = $this->db->conn->prepare("CALL RegisterUser(?, ?, ?, ?)");
        return $stmt->execute([$email, $hashedPassword, $role, $hashedPin]);
    }

    public function login($email, $password) {
        $stmt = $this->db->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>
