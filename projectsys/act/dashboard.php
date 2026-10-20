<?php

$pageTitle = "Sip n' Tell | Dashboard";
$pageSlug = 'dashboard';

require_once 'includes/header.php';

?>

<section class="dashboard-hero">
    <div>
        <p class="eyebrow">Welcome to the counter</p>
        <h2>Good coffee starts with a <span class="accent">good team.</span></h2>
        <p class="hero-intro">Keep the menu fresh and every shift ready for service.</p>
    </div>
</section>
<section class="dashboard-grid">
    <article class="dashboard-card">
        <h3>Menu Management</h3>
        <p>Review the current drinks and pastries available to customers.</p>
        <a href="menu.php" class="btn-primary">Manage Menu &rarr;</a>
    </article>
    <article class="dashboard-card">
        <h3>Employee Roster</h3>
        <p>Keep names, shift times, and staff files together for an easier handoff.</p>
        <a href="employees.php" class="btn-primary">Manage Employees &rarr;</a>
    </article>
    <article class="dashboard-card">
        <h3>Customer Management</h3>
        <p>Keep customer contact details organized and easy to update.</p>
        <a href="customers.php" class="btn-primary">Manage Customers &rarr;</a>
    </article>
    <article class="dashboard-card">
        <h3>Admin Management</h3>
        <p>Manage admin usernames and account credentials.</p>
        <a href="../admin/admin_list.php" class="btn-primary">Manage Admins &rarr;</a>
    </article>
</section>

<?php require_once 'includes/footer.php'; ?>
