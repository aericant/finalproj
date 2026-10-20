<?php require __DIR__ . '/view_helpers.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List</title><link rel="stylesheet" href="../act/assets/css/style.css">
    <style>
        .admin-page{width:min(1080px,90%);margin:48px auto}.admin-intro{margin-bottom:24px}
        .admin-intro h2{margin:0 0 8px;color:var(--coffee-dark);font-size:clamp(1.8rem,4vw,2.5rem)}
        .admin-intro p,.admin-help{color:#766252}.admin-card{padding:clamp(20px,4vw,36px)}
        .admin-card-heading{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px}
        .admin-card-heading h2{margin:0}.admin-add-link,.admin-submit{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:10px 18px;border:0;border-radius:5px;background:var(--terracotta);color:#fffaf2;font:bold 1rem Georgia,"Times New Roman",serif;text-decoration:none;cursor:pointer}
        .admin-table-wrap{overflow-x:auto;border:1px solid var(--line);border-radius:6px}.admin-table{min-width:480px;margin:0}
        .admin-actions{display:flex;gap:8px}.admin-action-link{display:inline-flex;padding:6px 12px;border-radius:4px;font-size:.9rem;font-weight:bold;text-decoration:none}
        .admin-edit-link{background:var(--parchment);color:var(--coffee-dark)}.admin-delete-link{background:#f3dfd5;color:#8b3e2c}
        .admin-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}.admin-form-actions{display:flex;gap:10px}.admin-field-full{grid-column:1/-1}
        .admin-help{display:block;margin-top:7px;font-size:.9rem}.admin-message:empty{display:none}
        @media(max-width:640px){.admin-page{width:92%;margin:28px auto}.admin-form-grid{grid-template-columns:1fr}.admin-field-full{grid-column:auto}}
    </style>
</head>
<body>
<div class="site-shell" id="mainWrapper">
    <input type="checkbox" id="sidebar-toggle">
    <header class="site-header"><div class="brand"><div class="brand-mark" aria-hidden="true">&#9749;</div><div><h1>Sip n' <span>Tell</span></h1><span class="brand-tagline">Administration</span></div></div><div class="header-actions"><label for="sidebar-toggle" class="sidebar-open-btn" aria-label="Open navigation">&#9776;</label></div></header>
    <div class="stripe-bar"></div><aside class="sidebar"><label for="sidebar-toggle" class="sidebar-close-btn" aria-label="Close navigation">&times;</label><?php $sidebarBase = '../act/'; require __DIR__ . '/../../includes/sidebar.php'; ?></aside><label for="sidebar-toggle" class="sidebar-overlay"></label>
    <main class="admin-page" id="pageContent">
        <?php if ($deleteConfirmationId !== null): ?>
            <section class="content-card delete-confirmation"><p class="eyebrow">Confirm deletion</p><h1>Delete admin?</h1><p>Are you sure you want to delete admin <strong><?php echo e($deleteConfirmationId); ?></strong>?</p><div class="delete-confirmation-actions"><a class="action-button action-delete" href="admin_list.php?yesdelete=<?php echo rawurlencode($deleteConfirmationId); ?>">Yes</a><a class="action-button action-cancel" href="admin_list.php">No</a></div></section>
        <?php else: ?>
        <div class="admin-intro"><p class="eyebrow">Administration</p><h2>Manage your administrators</h2><p>Manage admin accounts and their sign-in details.</p></div>
        <div class="content-stack">
            <section class="content-card admin-card"><div class="admin-card-heading"><h2>Admin List</h2><a class="admin-add-link" href="#adminForm">Add New Admin</a></div>
                <?php if ($adminMessage !== ''): ?><p class="notice admin-message"><?php echo e($adminMessage); ?></p><?php endif; ?>
                <?php if (empty($admins)): ?><p>No admin accounts found.</p><?php else: ?>
                <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Admin ID</th><th>Username</th><th>Actions</th></tr></thead><tbody>
                <?php foreach ($admins as $admin): $adminId = (string) $admin['admin_id']; ?><tr><td><span class="admin-id">#<?php echo e($adminId); ?></span></td><td><strong><?php echo e($admin['username']); ?></strong></td><td class="admin-actions"><a class="admin-action-link admin-edit-link" href="admin_list.php?editid=<?php echo rawurlencode($adminId); ?>#adminForm">Edit</a><a class="admin-action-link admin-delete-link" href="admin_list.php?deleteid=<?php echo rawurlencode($adminId); ?>">Delete</a></td></tr><?php endforeach; ?>
                </tbody></table></div><?php endif; ?>
            </section>
            <section class="content-card admin-card" id="adminForm"><div class="admin-card-heading"><h2><?php echo $editAdmin ? 'Edit Admin' : 'Add New Admin'; ?></h2></div>
                <p class="admin-help"><?php echo $editAdmin ? 'Update the username or enter a new password. Leave the password blank to keep the current one.' : 'Create an admin account with a username and a password of at least 8 characters.'; ?></p>
                <form class="admin-form" action="admin_list.php" method="post"><div class="admin-form-grid">
                    <input type="hidden" name="admin_id" value="<?php echo e($editAdmin['admin_id'] ?? ''); ?>">
                    <div><label for="username">Username</label><input name="username" type="text" id="username" autocomplete="username" maxlength="80" value="<?php echo e($editAdmin['username'] ?? ''); ?>" required></div>
                    <div><label for="password">Password</label><input name="password" type="password" id="password" autocomplete="new-password" minlength="8" <?php echo $editAdmin ? '' : 'required'; ?> placeholder="<?php echo $editAdmin ? 'Leave blank to keep current password' : ''; ?>"></div>
                    <div class="admin-field-full admin-form-actions"><button class="admin-submit" type="submit"><?php echo $editAdmin ? 'Update Admin' : 'Create Admin'; ?></button><?php if ($editAdmin): ?><a class="admin-action-link admin-edit-link" href="admin_list.php">Cancel</a><?php endif; ?></div>
                </div></form>
            </section>
        </div>
        <?php endif; ?>
    </main>
    <footer class="footer"><p>&copy; 2026 Sip n' Tell. All rights reserved.</p></footer>
</div>
</body>
</html>
