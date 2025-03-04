<?php
require_once __DIR__ . '/../core/Database.php';

class Article {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getArticles($userId) {
        $stmt = $this->db->conn->prepare('CALL GetArticles(?)');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticleById($userId, $article_id): mixed {
        $stmt = $this->db->conn->prepare('CALL GetArticleById(?, ?)');
        $stmt->execute([$userId, $article_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function deleteArticle($userId, $id) {
        $sql = "CALL DeleteArticle(?, ?)";
        $stmt = $this->db->conn->prepare($sql);
        return $stmt->execute([$userId, $id]);
    }  

    public function createArticle($userId, $name, $description, $taxed, $tax_rate, $discounted, $discount_percent, $discount_value, $unit_price) {
        $stmt = $this->db->conn->prepare('CALL CreateArticle(?, ?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$userId, $name, $description, $taxed, $tax_rate, $discounted, $discount_percent, $discount_value, $unit_price]);
    }

    public function updateArticle($userId, $article_id, $name, $description, $taxed, $tax_rate, $discounted, $discount_percent, $discount_value, $unit_price) {
        $stmt = $this->db->conn->prepare('CALL UpdateArticle(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$userId, $article_id, $name, $description, $taxed, $tax_rate, $discounted, $discount_percent, $discount_value, $unit_price]);
    }

}
?>
