<?php

if (!isset($pageTitle)) {
    $pageTitle = "Sip n' Tell";
}

if (!isset($pageSlug)) {
    $pageSlug = 'home';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <link rel="stylesheet" href="assets/css/style.css?v=3">

</head>

<body>

<input type="checkbox" id="sidebar-toggle">
<header class="site-header">
    <div class="brand">
        <div class="brand-mark" aria-hidden="true">&#9749;</div>
        <div>
            <h1>Sip n' <span>Tell</span></h1>
            <span class="brand-tagline">Where every sip tells a story</span>
        </div>
    </div>
    <div class="header-actions">
        <label for="sidebar-toggle" class="sidebar-open-btn" aria-label="Open navigation">&#9776;</label>
    </div>
</header>
<div class="stripe-bar"></div>
<aside class="sidebar">
    <label for="sidebar-toggle" class="sidebar-close-btn" aria-label="Close navigation">&times;</label>
    <?php require 'includes/sidebar.php'; ?>
</aside>
<label for="sidebar-toggle" class="sidebar-overlay"></label>
<main class="page-content">