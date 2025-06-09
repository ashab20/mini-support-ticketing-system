<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/TokenStore.php';
require_once __DIR__ . '/../helpers/Response.php';

class AuthController
{
    private $data;
    private $db;

    public function __construct($data, $db)
    {
        $this->data = $data;
        $this->db = $db;
    }

    public function register()
    {
        $name = trim($this->data['name'] ?? '');
        $email = trim($this->data['email'] ?? '');
        $password = $this->data['password'] ?? '';
        $role = $this->data['role'] ?? 'agent';

        if (!$name || !$email || !$password) {
            Response::json(['error' => 'Name, email and password are required.'], 400);
            return;
        }

        $userModel = new User();
        if ($userModel->getByEmail($email)) {
            Response::json(['error' => 'Email already exists.'], 409);
            return;
        }
        $userModel->create($name, $email, $password, $role);

        Response::json(['message' => 'User registered successfully.'], 201);
    }

    public function login()
    {
        $email = trim($this->data['email'] ?? '');
        $password = $this->data['password'] ?? '';

        if (!$email || !$password) {
            Response::json(['error' => 'Email and password are required.'], 400);
            return;
        }

        $userModel = new User();
        $user = $userModel->getByEmail($email);
        $checkPassword = sha1(md5($password));
        if (!$user || $checkPassword !== $user['password_hash']) {
            Response::json(['error' => 'Invalid credentials.'], 401);
            return;
        }

        $token = bin2hex(random_bytes(32));
        $tokenStore = new TokenStore($this->db);
        $tokenStore->storeToken($token, $user['id']);

        Response::json([
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ]
        ], 200);
    }

    public function logout()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';

        if (!$token) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing token in Authorization header.']);
            return;
        }

        $tokenStore = new TokenStore($this->db);
        if (!$tokenStore->deleteToken($token)) {
            http_response_code(401);
            Response::json(['error' => 'Invalid or expired token.'], 401);
            return;
        }

        Response::json(['message' => 'Logged out successfully.'], 200);
    }
}