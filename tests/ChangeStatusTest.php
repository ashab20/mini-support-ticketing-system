<?php

require_once __DIR__ . '/../middleware.php';
require_once __DIR__ . '/../controllers/TicketController.php';
require_once __DIR__ . '/../config/database.php';

class FakeAuth extends AuthMiddleware
{
    public function __construct() {}

    public function getUserId()
    {
        return 2;
    }
}

$data = ['status' => 'closed'];

$auth = new FakeAuth();
$db = Database::getInstance();
$controller = new TicketController($auth, $db);

ob_start();
$controller->changeStatus(3, $data);
$response = ob_get_clean();

echo "🧪 ChangeStatusTest Response:\n$response\n";