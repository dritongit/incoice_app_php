<?php
require_once __DIR__ . '/../core/Database.php';

class Contact {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getContacts($userId) {
        $stmt = $this->db->conn->prepare('CALL GetContacts(?)');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContactById($userId, $contactId) {
        $stmt = $this->db->conn->prepare('CALL GetContactById(?, ?)');
        $stmt->execute([$userId, $contactId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createContact($userId, $name, $alternative, $adress1, $address2, $address3, $town, $region, $postcode, $email1, $email2) {
        $stmt = $this->db->conn->prepare('CALL CreateContact(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$userId, $name, $alternative, $adress1, $address2, $address3, $town, $region, $postcode, $email1, $email2]);
    }

    public function deleteContact($id, $userId) {
        $sql = "CALL DeleteContact(?)";
        $stmt = $this->db->conn->prepare($sql);
        // $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        // $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute([$id]);
    }    
    
}
?>
