<?php
require_once("../connection/connection.php");
require_once("../models/User.php");
require_once("../Connection/cors.php");

$data = json_decode(file_get_contents("php://input"), true);

$response = [
    "status" => 400,
    "message" => "Invalid input"
];

if (!isset($data["full_name"], $data["email"], $data["password"])) {
    echo json_encode($response);
    return;
}

$find = User::findByEmail($mysqli, $data["email"]);

if ($find) {
    $response = [
        "status" => 409,
        "message" => "User already exists"
    ];
} else {
    $user = User::create($mysqli, $data);

    if ($user) {
        $response = [
            "status" => 201,
            "user" => $user->toArray()
        ];
    } else {
        $response = [
            "status" => 500,
            "message" => "Failed to create user"
        ];
    }
}

echo json_encode($response);