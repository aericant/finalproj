<?php

class EmployeeModel
{
    public function all()
    {
        return db_rows(db_query('SELECT employee_id, full_name, role, department, shift, file_name, status FROM employees ORDER BY full_name'));
    }

    public function nextId()
    {
        $result = db_query("SELECT employee_id FROM employees WHERE employee_id LIKE 'EMP-%' ORDER BY employee_id DESC LIMIT 1");
        $lastRow = $result ? mysqli_fetch_assoc($result) : null;
        if ($result) {
            mysqli_free_result($result);
        }
        $lastId = (string) ($lastRow['employee_id'] ?? 'EMP-000');
        $number = (int) preg_replace('/[^0-9]/', '', $lastId);
        return 'EMP-' . str_pad((string) ($number + 1), 3, '0', STR_PAD_LEFT);
    }

    public function save($employee, $upload)
    {
        global $connection;

        $uploadInfo = save_uploaded_jpg($upload, __DIR__ . '/../../images', 'employee_', (string) $employee['employee_id']);
        if (($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE && $uploadInfo['file_name'] === '') {
            return false;
        }
        if ($uploadInfo['file_name'] !== '') {
            $employee['file_name'] = $uploadInfo['file_name'];
        }

        $employeeId = db_escape((string) $employee['employee_id']);
        $name = db_escape((string) $employee['full_name']);
        $role = db_escape((string) $employee['role']);
        $department = db_escape((string) $employee['department']);
        $shift = db_escape((string) $employee['shift']);
        $fileName = db_escape((string) ($employee['file_name'] ?? ''));
        $status = db_escape((string) ($employee['status'] ?? 'Active'));
        $query = "INSERT INTO employees (employee_id, full_name, role, department, shift, file_name, status)
            VALUES ('$employeeId', '$name', '$role', '$department', '$shift', '$fileName', '$status')
            ON DUPLICATE KEY UPDATE full_name = VALUES(full_name), role = VALUES(role),
            department = VALUES(department), shift = VALUES(shift),
            file_name = COALESCE(NULLIF(VALUES(file_name), ''), file_name), status = VALUES(status)";
        return $connection && mysqli_query($connection, $query) !== false;
    }

    public function toggleStatus($employeeId)
    {
        $employeeId = db_escape($employeeId);
        return db_query("UPDATE employees SET status = IF(status = 'Active', 'On Leave', 'Active') WHERE employee_id = '$employeeId'") !== false;
    }

    public function delete($employeeId)
    {
        $employeeId = db_escape($employeeId);
        $rows = db_rows(db_query("SELECT file_name FROM employees WHERE employee_id = '$employeeId' LIMIT 1"));
        $fileName = (string) ($rows[0]['file_name'] ?? '');
        return delete_record("DELETE FROM employees WHERE employee_id = '$employeeId' LIMIT 1", $employeeId, 'Employee_', $fileName) !== false;
    }
}
