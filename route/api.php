<?php
require_once __DIR__ . '/../helpers/Response.php';

// Headers 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$version = 'api/v1/';

switch (true) {
    case preg_match("/$version\/?/", $request) && $method == 'GET':
        $data = [
            'message' => 'Welcome to Ticketing System API',
            'status' => 'success'
        ];
        Response::json($data, 200);
        break;

    default:
        $data = [
            'message' => 'Not Found',
            'status' => 'error',
        ];
        Response::json($data, 404);
}