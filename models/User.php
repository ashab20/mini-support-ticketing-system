<?php

require_once __DIR__ . '/../config/database.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAllUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $password, $role)
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $passwordHash = sha1(md5($password));
        $stmt->execute([$name, $email, $passwordHash, $role]);
        return $this->db->lastInsertId();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $email, $password, $role)
    {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, password_hash = ?, role = ? WHERE id = ?");
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$name, $email, $passwordHash, $role, $id]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}