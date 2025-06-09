<?php
require_once __DIR__ . '/../config/database.php';

class Department
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM departments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM departments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO departments (name) VALUES (?)");
        $stmt->execute([$data['name']]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE departments SET name = ? WHERE id = ?");
        $stmt->execute([$data['name'], $id]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM departments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}