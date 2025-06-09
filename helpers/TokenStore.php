<?php

class TokenStore
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function storeToken($token, $userId)
    {
        $stmt = $this->db->prepare("INSERT INTO tokens (token, user_id, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([$token, $userId]);
    }

    public function getUserIdByToken($token)
    {
        $stmt = $this->db->prepare("SELECT user_id FROM tokens WHERE token = ?");
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['user_id'] : null;
    }

    public function deleteToken($token)
    {
        $stmt = $this->db->prepare("DELETE FROM tokens WHERE token = ?");
        return $stmt->execute([$token]);
    }
}