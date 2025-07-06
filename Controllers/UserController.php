<?php

require_once __DIR__ . '/../controllers/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../connection/cors.php';

class UserController extends BaseController {

    public function register() {
        global $mysqli;

        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data["full_name"], $data["email"], $data["password"])) {
                self::error("Missing required fields", 400);
                return;
            }

            $existing = User::findByEmail($mysqli, $data["email"]);

            if ($existing) {
                self::error("User already exists", 409);
                return;
            }

            $user = User::create($mysqli, $data);

            if ($user) {
                self::success($user->toArray(), 201);
            } else {
                self::error("Failed to create user", 500);
            }

        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }

    public function login() {
        global $mysqli;

        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data["email"], $data["password"])) {
                self::error("Missing credentials", 400);
                return;
            }

            $user = User::findByEmail($mysqli, $data["email"]);

            if ($user && password_verify($data["password"], $user->getPasswordHash())) {
                self::success([
                    "message" => "Login successful",
                    "user" => $user->toArray()
                ], 200);
            } else {
                self::error("Invalid email or password", 401);
            }

        } catch (Exception $e) {
            self::error("Server error: " . $e->getMessage(), 500);
        }
    }
}
