<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../controllers/TicketController.php';
require_once __DIR__ . '/../middleware.php';

// Fake Auth
class FakeAuth extends AuthMiddleware
{
    public function __construct() {}
    public function getUserId()
    {
        return 1;
    }
}

// Instantiate
$auth = new FakeAuth();
$db = Database::getInstance();
$controller = new TicketController($auth, $db);


ob_start();
$controller->assignToTicket(3);
$response = ob_get_clean();

echo "assignToTicket Response:\n$response\n";


$data = json_decode($response, true);
if ($data['status'] === 'success' && $data['data'] > 0) {
    echo "Test Passed\n";
} else {
    echo "Test Failed: " . $data['message'] . "\n";
}