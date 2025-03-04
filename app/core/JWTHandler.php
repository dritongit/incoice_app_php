<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler {
    private static $secret_key = "super_secret_key";

    public static function generateToken($userId, $role) {
        $payload = [
            "user_id" => $userId,
            "role" => $role,
            "iat" => time(),
            "exp" => time() + (60 * 60 * 24 * 60) // 2 monnths
            // "exp" => time() + (60 * 60) // 1 orë
            
        ];

        return JWT::encode($payload, self::$secret_key, 'HS256');
    }

    public static function verifyToken($token) {
        try {
            $decoded = Firebase\JWT\JWT::decode($token, new Firebase\JWT\Key(self::$secret_key, 'HS256'));
    
            // Check expiration manually (should not be necessary, but just in case)
            if ($decoded->exp < time()) {
                http_response_code(401);
                echo json_encode(["error" => "Token has expired"]);
                exit;
            }
    
            return $decoded;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
            exit;
        }
    }
    
}
?>
