<?php

require_once __DIR__ . '/../middleware.php';
require_once __DIR__ . '/../controllers/TicketController.php';
require_once __DIR__ . '/../config/database.php';

// Fake Auth
class FakeAuth extends AuthMiddleware
{
    public function __construct() {}

    public function getUserId()
    {
        return 2;
    }
}

$_POST['note'] = 'This is a test note.';

$auth = new FakeAuth();
$db = Database::getInstance();
$controller = new TicketController($auth, $db);

ob_start();
$controller->addNote(3);
$response = ob_get_clean();

echo " AddTicketNoteTest Response:\n$response\n";