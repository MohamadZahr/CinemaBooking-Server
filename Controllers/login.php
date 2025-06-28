<?php
require_once("../connection/connection.php");
require_once("../models/User.php");
require_once("../Connection/cors.php");

$data = json_decode(file_get_contents("php://input"), true);

$response = [
    "status" => 400,
    "message" => "Invalid email or password"
];

if (!isset($data["email"], $data["password"])) {
    echo json_encode($response);
    return;
}

$email = $data["email"];
$password = $data["password"];

$user = User::findByEmail($mysqli, $email);

if ($user && password_verify($password, $user->getPasswordHash())) {
    $response = [
        "status" => 200,
        "message" => "Login successful",
        "user" => $user->toArray()
    ];
}

echo json_encode($response);