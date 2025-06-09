<?php

require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../helpers/Response.php';

class DepartmentController
{
    public function getAll()
    {
        $department = new Department();
        $departments = $department->getAll();
        Response::json($departments);
    }

    public function getById($id)
    {
        $department = new Department();
        $department = $department->getById($id);
        Response::json($department);
    }

    public function create($data)
    {
        try {
            if (empty($data['name'])) {
                Response::json(['message' => 'Department name is required', 'status' => 'error'], 400);
                return;
            }

            $department = new Department();
            $department = $department->create($data);
            Response::json(['message' => 'Department created successfully', 'status' => 'success'], 201);
        } catch (Exception $e) {
            Response::json([
                'message' => 'Department creation failed',
                'status' => 'error',
                //  'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id, $data)
    {
        try {
            if (empty($data['name'])) {
                Response::json(['message' => 'Department name is required', 'status' => 'error'], 400);
                return;
            }

            $department = new Department();
            $department = $department->update($id, $data);
            Response::json(['message' => 'Department updated successfully', 'data' => $department, 'status' => 'success'], 200);
        } catch (Exception $e) {
            Response::json(['message' => 'Department update failed', 'status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $department = new Department();
            $department = $department->delete($id);
            Response::json(['message' => 'Department deleted successfully', 'data' => $department, 'status' => 'success'], 200);
        } catch (Exception $e) {
            Response::json(['message' => 'Department deletion failed', 'status' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}