<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../models/EmployeeModel.php';

class EmployeeController
{
    public function index(): void
    {
        session_start();
        $model = new EmployeeModel();
        $message = '';
        $deleteConfirmationId = isset($_GET['deleteid']) ? (string) $_GET['deleteid'] : null;

        if (isset($_GET['yesdelete'])) {
            $model->delete((string) $_GET['yesdelete']);
            redirect_to('employees.php');
        }
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $action = (string) ($_POST['action'] ?? '');
            if ($action === 'toggle_status') {
                $message = $model->toggleStatus(trim((string) ($_POST['employee_id'] ?? '')))
                    ? 'Employee status updated.'
                    : 'The employee status could not be updated.';
            } else {
                $employee = [
                    'employee_id' => trim((string) ($_POST['employee_id'] ?? '')),
                    'full_name' => trim((string) ($_POST['full_name'] ?? '')),
                    'role' => trim((string) ($_POST['role'] ?? '')),
                    'department' => trim((string) ($_POST['department'] ?? '')),
                    'shift' => trim((string) ($_POST['shift'] ?? '')),
                    'file_name' => '',
                    'status' => trim((string) ($_POST['status'] ?? 'Active')),
                ];
                if (in_array('', [$employee['employee_id'], $employee['full_name'], $employee['role'], $employee['department'], $employee['shift']], true)) {
                    $message = 'Please complete all employee details.';
                } elseif ($model->save($employee, $_FILES['employee_file'] ?? [])) {
                    $message = 'Employee record saved.';
                } else {
                    $message = 'The employee record could not be saved.';
                }
            }
        }
        $employees = $model->all();
        $newEmployeeId = $model->nextId();
        $pageTitle = "Sip n' Tell | Employees";
        $pageSlug = 'employees';
        require __DIR__ . '/../views/employees.php';
    }
}
