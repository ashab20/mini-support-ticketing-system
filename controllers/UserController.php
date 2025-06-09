<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Response.php';

class UserController
{

    public function getAllUsers()
    {
        $user = new User();
        $users = $user->getAllUsers();
        Response::json([
            'message' => 'User List',
            'status' => 'success',
            'data' => $users
        ], 200);
    }

    public function createUser($data)
    {
        $user = new User();
        $user = $user->create(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role']
        );
        Response::json([
            'message' => 'User Created',
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function getSingleUser($id)
    {
        $user = new User();
        $user = $user->findById($id);
        Response::json([
            'message' => 'User Found',
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function updateUser($data)
    {
        $user = new User();
        $user = $user->update(
            $data['id'],
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role']
        );
        Response::json([
            'message' => 'User Updated',
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function deleteUser($id)
    {
        $user = new User();
        $user = $user->delete($id);
        Response::json([
            'message' => 'User Deleted',
            'status' => 'success',
            'data' => $user
        ], 200);
    }
}