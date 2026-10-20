<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../models/CustomerModel.php';

class CustomerController
{
    public function index(): void
    {
        session_start();
        $model = new CustomerModel();
        $model->ensureSchema();
        $message = '';
        $editCustomer = null;
        $deleteConfirmationId = isset($_GET['deleteid']) ? (string) $_GET['deleteid'] : null;

        if (isset($_GET['yesdelete'])) {
            $model->delete((string) $_GET['yesdelete']);
            redirect_to('customers.php');
        }
        if (($_GET['action'] ?? '') === 'edit' && isset($_GET['customer_id'])) {
            $editCustomer = $model->find((string) $_GET['customer_id']);
            if (!$editCustomer) {
                $message = 'The customer record could not be found.';
            }
        }
        $isEditing = $editCustomer !== null || ($_POST['edit_mode'] ?? '') === '1';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $customer = [
                'customer_id' => trim((string) ($_POST['customer_id'] ?? '')),
                'full_name' => trim((string) ($_POST['full_name'] ?? '')),
                'contact_number' => trim((string) ($_POST['contact_number'] ?? '')),
                'payment_method' => trim((string) ($_POST['payment_method'] ?? '')),
            ];
            if ($customer['customer_id'] === '' || $customer['full_name'] === '') {
                $message = 'Please complete the customer ID and name.';
            } elseif ($model->save($customer)) {
                $message = 'Customer record ' . ($isEditing ? 'updated' : 'saved') . '.';
                if ($isEditing) {
                    $editCustomer = $customer;
                }
            } else {
                $message = 'The customer record could not be saved.';
            }
        }

        $customers = $model->all();
        $newCustomerId = $isEditing ? (string) ($editCustomer['customer_id'] ?? '') : $model->nextId();
        $pageTitle = "Sip n' Tell | Customers";
        $pageSlug = 'customers';
        require __DIR__ . '/../views/customers.php';
    }
}
