<?php

require_once __DIR__ . '/../middleware.php';
require_once __DIR__ . '/../controllers/TicketController.php';
require_once __DIR__ . '/../config/database.php';

// Mocked Auth
class FakeAuth extends AuthMiddleware
{
    public function __construct() {}

    public function getUserId()
    {
        return 2;
    }
}

// Fake POST data
$_POST['title'] = 'Test Ticket Title';
$_POST['description'] = 'Test Ticket Description';
$_POST['department_id'] = 1;

$auth = new FakeAuth();
$db = Database::getInstance();
$controller = new TicketController($auth, $db);

ob_start();
$controller->submitTicket();
$response = ob_get_clean();

echo "SubmitTicketTest Response:\n$response\n";