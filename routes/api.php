<?php
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware.php';
require_once __DIR__ . '/../controllers/DepartmentController.php';
require_once __DIR__ . '/../controllers/TicketController.php';

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Clean path
$request = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];
$version = '/api/v1';
// $data = json_decode(file_get_contents("php://input"), true) ?? [];

$db = Database::getInstance();

switch (true) {
    // Base route
    case $request === "$version" && $method === 'GET':
        Response::json([
            'message' => 'Welcome to Ticketing System API',
            'status' => 'success'
        ], 200);
        break;

    case $request === "$version/register" && $method === 'POST':
        $authController = new AuthController($_POST, $db);
        $authController->register();
        break;

    case $request === "$version/login" && $method === 'POST':
        $authController = new AuthController($_POST, $db);
        $authController->login();
        break;

    case $request === "$version/logout" && $method === 'POST':
        $authController = new AuthController($data, $db);
        $authController->logout();
        break;


    // GET /api/v1/users — Get all users
    case $request === "$version/users" && $method === 'GET':
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin', 'Agent']);

        $userController = new UserController();
        $userController->getAllUsers();
        break;

    // POST /api/v1/users — Create user
    case $request === "$version/users" && $method === 'POST':
        $userController = new UserController();
        $userController->createUser($_POST);
        break;

    // GET /api/v1/users/1
    case preg_match("#^$version/users/(\d+)$#", $request, $matches) === 1 && $method === 'GET':
        $userId = $matches[1];
        $userController = new UserController();
        $userController->getSingleUser($userId);
        break;

    // PUT /api/v1/users/1
    case preg_match("#^$version/users/(\d+)$#", $request, $matches) === 1 && $method === 'PUT':
        $userId = $matches[1];
        $userController = new UserController();
        $userController->updateUser($_POST);
        break;

    // DELETE /api/v1/users/1
    case preg_match("#^$version/users/(\d+)$#", $request, $matches) === 1 && $method === 'DELETE':
        $userId = $matches[1];
        $userController = new UserController();
        $userController->deleteUser($userId);
        break;

    // departments
    case $request === "$version/departments" && $method === 'GET':
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin']);

        $departmentController = new DepartmentController();
        $departmentController->getAll();
        break;

    case $request === "$version/departments" && $method === 'POST':
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin']);

        $departmentController = new DepartmentController();
        $departmentController->create($_POST);
        break;

    case preg_match("#^$version/departments/(\d+)$#", $request, $matches) === 1 && $method === 'PUT':
        $departmentId = $matches[1];
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin']);
        parse_str(file_get_contents("php://input"), $putData);

        $departmentController = new DepartmentController();
        $departmentController->update($departmentId, $putData);
        break;


    case preg_match("#^$version/departments/(\d+)$#", $request, $matches) === 1 && $method === 'DELETE':
        $departmentId = $matches[1];
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin']);

        $departmentController = new DepartmentController();
        $departmentController->delete($departmentId);
        break;

    case preg_match("#^$version/departments/(\d+)$#", $request, $matches) === 1 && $method === 'GET':
        $departmentId = $matches[1];
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin']);

        $departmentController = new DepartmentController();
        $departmentController->getById($departmentId);
        break;

    // tickets
    case $request === "$version/tickets" && $method === 'GET':
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin', 'Agent', 'User']);

        $ticketController = new TicketController($auth, $db);
        $ticketController->getAll();
        break;
    case $request === "$version/tickets" && $method === 'POST':
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin', 'Agent', 'User']);

        $ticketController = new TicketController($auth, $db);
        $ticketController->submitTicket();
        break;
    case preg_match("#^$version/tickets/(\d+)/assign$#", $request, $matches) && $method === 'POST':
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin', 'Agent']);

        $ticketController = new TicketController($auth, $db);
        $ticketController->assignToTicket($matches[1]);
        break;

    case preg_match("#^$version/tickets/(\d+)/status$#", $request, $matches) && $method === 'POST':
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin', 'Agent']);

        $ticketController = new TicketController($auth, $db);
        $ticketController->changeStatus($matches[1], $_POST);
        break;

    case preg_match("#^$version/tickets/(\d+)/notes$#", $request, $matches) && $method === 'POST':
        $auth = new AuthMiddleware($db);
        $auth->authenticate(['Admin', 'Agent']);

        $ticketController = new TicketController($auth, $db);
        $ticketController->addNote($matches[1]);
        break;

    // Fallback
    default:
        Response::json([
            'message' => 'Not Found',
            'status' => 'error',
        ], 404);
}