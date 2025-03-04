<?php
require_once __DIR__ . '/../core/Database.php';

class Invoice {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createInvoice($userId, $amount) {
        $stmt = $this->db->conn->prepare("CALL AddInvoice(?, ?)");
        return $stmt->execute([$userId, $amount]);
    }

    public function getInvoices($userId) {
        $stmt = $this->db->conn->prepare("CALL GetInvoices(?)");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInvoice($invoiceId) {
        $stmt = $this->db->conn->prepare("CALL GetInvoice(?)");
        $stmt->execute([$invoiceId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInvoiceSub($invoiceId) {
        $stmt = $this->db->conn->prepare("CALL GetInvoiceSub(?)");
        $stmt->execute([$invoiceId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInvoicePayments($invoiceId) {
        $stmt = $this->db->conn->prepare("CALL GetInvoicePayments(?)");
        $stmt->execute([$invoiceId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
