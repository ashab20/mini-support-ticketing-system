<?php

require_once __DIR__ . '/../config/database.php';

class Ticket
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT tickets.*, tn.note, d.name as department_name, u.name as assigned_by_name FROM tickets LEFT JOIN ticket_notes tn ON tickets.id = tn.ticket_id LEFT JOIN departments d ON tickets.department_id = d.id LEFT JOIN users u ON tickets.assigned_by = u.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO tickets (title, description, status, user_id, department_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['title'], $data['description'], $data['status'], $data['user_id'], $data['department_id']]);
        return $this->db->lastInsertId();
    }

    public function assignAgent($id, $userId)
    {
        // echo "id: $id";
        // echo "userId: $userId";
        // exit;
        $stmt = $this->db->prepare("UPDATE tickets SET assigned_by = ? WHERE id = ?");
        $stmt->execute([$userId, $id]);
        return $stmt->rowCount();
    }

    public function changeStatus($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE tickets SET status = ? WHERE id = ?");
        $stmt->execute([$data['status'], $id]);
        return $stmt->rowCount();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE tickets SET title = ?, description = ?, status = ?, user_id = ?, department_id = ? WHERE id = ?");
        $stmt->execute([$data['title'], $data['description'], $data['status'], $data['user_id'], $data['department_id'], $id]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tickets WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}