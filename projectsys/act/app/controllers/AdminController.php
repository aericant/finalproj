<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../models/AdminModel.php';

class AdminController
{
    public function index(): void
    {
        $model = new AdminModel();
        $model->ensureSchema();
        $adminMessage = '';
        $deleteConfirmationId = isset($_GET['deleteid']) ? (string) $_GET['deleteid'] : null;
        $editAdmin = isset($_GET['editid']) ? $model->find((string) $_GET['editid']) : null;
        if (isset($_GET['editid']) && $editAdmin === null) {
            $adminMessage = 'The admin account could not be found.';
        }

        if (isset($_GET['yesdelete'])) {
            $model->delete((string) $_GET['yesdelete']);
            redirect_to('admin_list.php');
        }
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['username'])) {
            $adminId = trim((string) ($_POST['admin_id'] ?? ''));
            $username = trim((string) ($_POST['username'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            if ($username === '') {
                $adminMessage = 'Please enter a username.';
            } elseif (strlen($username) > 80) {
                $adminMessage = 'The username must be 80 characters or fewer.';
            } elseif ($adminId === '' && $password === '') {
                $adminMessage = 'Please enter a password.';
            } elseif ($password !== '' && strlen($password) < 8) {
                $adminMessage = 'The password must be at least 8 characters long.';
            } else {
                try {
                    if ($model->save($adminId, $username, $password)) {
                        redirect_to('admin_list.php');
                    }
                    $adminMessage = mysqli_errno($connection) === 1062
                        ? 'That username is already in use.'
                        : 'The admin account could not be saved.';
                } catch (mysqli_sql_exception $exception) {
                    if ($exception->getCode() === 1062) {
                        $adminMessage = 'That username is already in use.';
                    } else {
                        throw $exception;
                    }
                }
            }
            $editAdmin = ['admin_id' => $adminId, 'username' => $username];
        }

        $admins = $model->all();
        require __DIR__ . '/../views/admin.php';
    }
}
