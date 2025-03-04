<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../models/User.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"));

        if (!$data->email || !$data->password || !$data->role || !$data->pin) {
            echo json_encode(["error" => "Të gjitha fushat janë të detyrueshme!"]);
            return;
        }

        if ($this->userModel->register($data->email, $data->password, $data->role, $data->pin)) {
            echo json_encode(["message" => "Përdoruesi u regjistrua me sukses!"]);
        } else {
            echo json_encode(["error" => "Gabim gjatë regjistrimit!"]);
        }
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"));
        $user = $this->userModel->login($data->email, $data->password);

        if ($user) {
            $jwtHadler = new JWTHandler();
            $jwt = $jwtHadler->generateToken($user['id'], $user['role']);

            echo json_encode(["token" => $jwt, "role" => $user['role']]);
        } else {
            echo json_encode(["error" => "Email ose fjalëkalim i pasaktë!"]);
        }
    }
}
?>
