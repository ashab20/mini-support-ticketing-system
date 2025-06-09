<?php

class TicketNote
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO ticket_notes (ticket_id, user_id, note, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$data['ticket_id'], $data['user_id'], $data['note']]);
        return $this->db->lastInsertId();
    }
}