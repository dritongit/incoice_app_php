<?php
require_once __DIR__ . '/JWTHandler.php';

class Middleware {
    public static function checkAuth() {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Authorization header missing"]);
            exit; // Stop further execution
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);

        // Secret key (MUST MATCH the key used in token generation)
        $secretKey = 'your_secret_key';

        // JWT structure check (header.payload.signature)
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid token format"]);
            exit;
        }

        // Decode payload (without verification)
        $payload = json_decode(base64_decode($parts[1]), true);

        // Check if payload is valid
        if (!$payload || !isset($payload['user_id']) || !isset($payload['exp'])) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid token"]);
            exit;
        }

        // Check token expiration
        if ($payload['exp'] < time()) {
            http_response_code(401);
            echo json_encode(["error" => "Token has expired"]);
            exit;
        }

        // If everything is fine, return the decoded payload
        return (object) $payload;
    }
}



?>
