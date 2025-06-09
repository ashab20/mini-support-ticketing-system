<?php

require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/FileUploader.php';
require_once __DIR__ . '/../middleware.php';
require_once __DIR__ . '/../models/TicketNote.php';

class TicketController
{

    private $auth;
    private $db;

    public function __construct($auth, $db)
    {
        $this->auth = $auth;
        $this->db = $db;
    }

    public function getAll()
    {
        $ticket = new Ticket();
        $tickets = $ticket->getAll();
        Response::json($tickets);
    }

    public function getById($id)
    {
        $ticket = new Ticket();
        $ticket = $ticket->getById($id);
        Response::json($ticket);
    }

    public function submitTicket()
    {
        $userId = $this->auth->getUserId();

        try {
            $title = $_POST['title'] ?? null;
            $description = $_POST['description'] ?? null;
            $departmentId = $_POST['department_id'] ?? null;

            if (empty($title)) {
                Response::json(['message' => 'Ticket title is required', 'status' => 'error'], 400);
                return;
            }

            if (empty($description)) {
                Response::json(['message' => 'Ticket description is required', 'status' => 'error'], 400);
                return;
            }

            if (empty($departmentId)) {
                Response::json(['message' => 'Ticket department is required', 'status' => 'error'], 400);
                return;
            }

            $data = [
                'title' => $title,
                'description' => $description,
                'department_id' => $departmentId,
                'user_id' => $userId,
                'assigned_by' => null,
                'status' => 'open',
                'attachment' => null,
            ];

            // Handle file upload if exists
            if (!empty($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                $uploader = new FileUploader();
                $filePath = $uploader->uploadFile($_FILES['attachment']);
                if ($filePath === false) {
                    Response::json(['message' => 'File upload failed', 'status' => 'error'], 500);
                    return;
                }
                $data['attachment'] = $filePath;
            }

            // Create ticket
            $ticket = new Ticket();
            $ticket->create($data);

            Response::json(['message' => 'Ticket created successfully', 'status' => 'success'], 201);
        } catch (Exception $e) {
            Response::json(['message' => 'Ticket creation failed', 'status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }


    public function assignToTicket($ticketId)
    {
        try {
            var_dump($this->auth);
            $userId = $this->auth->getUserId();
            var_dump(get_class($this->auth));
            $ticket = new Ticket();
            $result = $ticket->assignAgent($ticketId, $userId);
            if ($result) {
                Response::json([
                    'message' => 'Ticket assigned to you',
                    'status' => 'success'
                ], 200);
            } else {
                Response::json([
                    'message' => 'Ticket assignment failed',
                    'status' => 'error'
                ], 400);
            }
        } catch (Exception $e) {
            Response::json([
                'message' => 'Ticket assignment failed',
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changeStatus($id, $data)
    {
        try {
            $ticket = new Ticket();
            $ticket = $ticket->changeStatus($id, $data);
            Response::json(['message' => 'Ticket status changed successfully', 'status' => 'success'], 200);
        } catch (Exception $e) {
            Response::json(['message' => 'Ticket status change failed', 'status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }


    public function addNote($ticketId)
    {
        $userId = $this->auth->getUserId();
        $note = $_POST['note'] ?? '';

        if (!$note) {
            Response::json(['message' => 'Note is required'], 400);
            return;
        }

        $noteModel = new TicketNote($this->db);
        $noteModel->create([
            'user_id' => $userId,
            'ticket_id' => $ticketId,
            'note' => $note
        ]);

        Response::json(['message' => 'Note added'], 201);
    }

    public function update($id, $data)
    {
        try {
            if (empty($data['title'])) {
                Response::json(['message' => 'Ticket title is required', 'status' => 'error'], 400);
                return;
            }

            if (empty($data['description'])) {
                Response::json(['message' => 'Ticket description is required', 'status' => 'error'], 400);
                return;
            }

            if (empty($data['department_id'])) {
                Response::json(['message' => 'Ticket department is required', 'status' => 'error'], 400);
                return;
            }

            $ticket = new Ticket();
            $ticket = $ticket->update($id, $data);
            Response::json(['message' => 'Ticket updated successfully', 'status' => 'success'], 200);
        } catch (Exception $e) {
            Response::json(['message' => 'Ticket update failed', 'status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $ticket = new Ticket();
            $ticket = $ticket->delete($id);
            Response::json(['message' => 'Ticket deleted successfully', 'status' => 'success'], 200);
        } catch (Exception $e) {
            Response::json(['message' => 'Ticket deletion failed', 'status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}