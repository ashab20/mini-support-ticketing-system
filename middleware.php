<?php

require_once __DIR__ . '/helpers/TokenStore.php';
require_once __DIR__ . '/helpers/Response.php';


class AuthMiddleware
{
    private $db;
    private $userId;
    private $role;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function authenticate(array $allowedRoles = [])
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            Response::json(['error' => 'Authorization token missing or malformed'], 401);
            exit;
        }

        $token = trim(str_replace('Bearer', '', $authHeader));

        $tokenStore = new TokenStore($this->db);
        $userId = $tokenStore->getUserIdByToken($token);

        if (!$userId) {
            Response::json(['error' => 'Invalid or expired token'], 401);
            exit;
        }

        // Fetch user role
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !isset($user['role'])) {
            Response::json(['error' => 'User role not found'], 403);
            exit;
        }

        $this->userId = $userId;
        $this->role = $user['role'];

        // Optional: Check if role is allowed
        if (!empty($allowedRoles) && !in_array($this->role, $allowedRoles)) {
            Response::json(['error' => 'Forbidden: Insufficient permission'], 403);
            exit;
        }
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getRole()
    {
        return $this->role;
    }
}