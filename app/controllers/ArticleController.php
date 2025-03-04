<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../models/Article.php';
require_once __DIR__ . '/../core/Middleware.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class ArticleController {
    private $articleModel;

    public function __construct() {
        $this->articleModel = new Article();
    }

    public function getAtricles($decodedToken) {
        $articles = $this->articleModel->getArticles($decodedToken->user_id);
        echo json_encode($articles);
    }

    public function getArticleById($decodedToken, $article_id) {
        $article = $this->articleModel->getArticleById($decodedToken->user_id, $article_id);
        echo json_encode($article);
    }
    public function createArticle($decodedToken) {

        $userId = $decodedToken->user_id;

        $data = json_decode(file_get_contents("php://input"));
        if (!$data->name) {
            echo json_encode(["error" => "Name is requred!"]);
            return;
        }

        if ($this->articleModel->createArticle($userId, $data->name, $data->description, $data->taxed, $data->tax_rate, $data->discounted, $data->discount_percent, $data->discout_value, $data->unit_price)) {
            echo json_encode(["message" => "Article saved successfully"]);
        } else {
            echo json_encode(["error" => "Error while saving article!"]);
        }
    }

    public function deleteArticle($decodedToken, $id) {
        // Check authentication
        $userId = $decodedToken->user_id;
    
        // Fetch the contact to verify ownership
        $article = $this->articleModel->getArticleById($userId, $id);
    
        if (!$article) {
            echo json_encode(["error" => "Article not found!"]);
            return;
        }
    
        // Security check: Ensure the contact belongs to the authenticated user
        if ($article['created_by'] !== $userId) {
            echo json_encode(["error" => "Unauthorized to delete this contact!"]);
            return;
        }
    
        // Proceed with deletion if authorized
        if ($this->articleModel->deleteArticle($id, $userId)) {
            echo json_encode(["message" => "Article deleted successfully"]);
        } else {
            echo json_encode(["error" => "Error while deleting article!"]);
        }
    }    

    public function updateArticle($decodedToken, $id) {
        // Check authentication
        $userId = $decodedToken->user_id;
    
        // Fetch the article to verify ownership
        $article = $this->articleModel->getArticleById($userId, $id);
    
        if (!$article) {
            echo json_encode(["error" => "Article not found!"]);
            return;
        }
    
        // Security check: Ensure the article belongs to the authenticated user
        if ($article['created_by'] !== $userId) {
            echo json_encode(["error" => "Unauthorized to update this article!"]);
            return;
        }
    
        // Get the request data
        $data = json_decode(file_get_contents("php://input"));
    
        // Validate required fields
        if (!$data->name) {
            echo json_encode(["error" => "Name is required!"]);
            return;
        }
    
        // Proceed with updating the article
        if ($this->articleModel->updateArticle(
            $userId,
            $id,
            $data->name,
            $data->description,
            $data->taxed,
            $data->tax_rate,
            $data->discounted,
            $data->discount_percent,
            $data->discount_value,
            $data->unit_price,
        )) {
            echo json_encode(["message" => "Article updated successfully"]);
        } else {
            echo json_encode(["error" => "Error while updating article!"]);
        }
    }
    
}