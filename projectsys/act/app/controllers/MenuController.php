<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../models/MenuModel.php';

class MenuController
{
    public function index(): void
    {
        session_start();
        $model = new MenuModel();
        $model->ensureSchema();
        $message = '';
        $deleteConfirmationId = isset($_GET['deleteid']) ? (string) $_GET['deleteid'] : null;

        if (isset($_GET['yesdelete'])) {
            $model->delete((string) $_GET['yesdelete']);
            redirect_to('menu.php');
        }
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $action = (string) ($_POST['action'] ?? '');
            if ($action === 'toggle_status') {
                $message = $model->toggleStatus(trim((string) ($_POST['item_id'] ?? '')))
                    ? 'Menu item status updated.'
                    : 'The menu item status could not be updated.';
            } elseif ($action === 'save') {
                $item = [
                    'item_id' => trim((string) ($_POST['item_id'] ?? '')),
                    'item_name' => trim((string) ($_POST['item_name'] ?? '')),
                    'category' => trim((string) ($_POST['category'] ?? '')),
                    'price' => (float) ($_POST['price'] ?? 0),
                    'file_name' => '',
                    'status' => trim((string) ($_POST['status'] ?? 'Available')),
                ];
                $categories = ['Coffee', 'Frappe', 'Tea', 'Pastry'];
                if ($item['item_id'] === '' || $item['item_name'] === '' || !in_array($item['category'], $categories, true) || $item['price'] < 0) {
                    $message = 'Please complete the menu item details.';
                } elseif ($model->save($item, $_FILES['menu_file'] ?? [])) {
                    $message = 'Menu item saved successfully.';
                } else {
                    $message = 'The menu item could not be saved.';
                }
            }
        }
        $menuItems = $model->all();
        $newMenuId = $model->nextId();
        $pageTitle = "Sip n' Tell | Daily Menu";
        $pageSlug = 'menu';
        require __DIR__ . '/../views/menu.php';
    }
}
