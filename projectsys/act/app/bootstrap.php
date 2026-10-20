<?php

require_once __DIR__ . '/../includes/db.php';

function redirect_to($location)
{
    header('Location: ' . $location);
    exit();
}
