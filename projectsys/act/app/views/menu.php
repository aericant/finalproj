<?php require __DIR__ . '/view_helpers.php'; require __DIR__ . '/../../includes/header.php'; ?>

<?php if ($deleteConfirmationId !== null): ?>
    <section class="content-stack"><article class="content-card delete-confirmation">
        <p class="eyebrow">Confirm deletion</p><h2>Delete menu item?</h2>
        <p>Do you really want to delete this record with ID of <strong><?php echo e($deleteConfirmationId); ?></strong>?</p>
        <div class="delete-confirmation-actions"><a class="action-button action-delete" href="menu.php?yesdelete=<?php echo rawurlencode($deleteConfirmationId); ?>">Yes</a><a class="action-button action-cancel" href="menu.php">No</a></div>
    </article></section>
<?php else: ?>
<section class="content-stack">
    <article class="content-card">
        <p class="eyebrow">Daily service</p><h2>Menu Management</h2>
        <?php if ($message !== ''): ?><p class="notice"><?php echo e($message); ?></p><?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="employee-form">
            <input type="hidden" name="action" value="save">
            <label for="item_id">Item ID</label><input id="item_id" name="item_id" type="text" value="<?php echo e($newMenuId); ?>" required>
            <label for="item_name">Menu item</label><input id="item_name" name="item_name" type="text" required>
            <label for="category">Category</label><select id="category" name="category" required><option>Coffee</option><option>Frappe</option><option>Tea</option><option>Pastry</option></select>
            <label for="price">Price</label><input id="price" name="price" type="number" step="0.01" min="0" required>
            <label for="status">Initial status</label><select id="status" name="status"><option>Available</option><option>Sold Out</option></select>
            <label for="menu_file">Menu item JPG photo</label><input id="menu_file" name="menu_file" type="file" accept="image/jpeg,.jpg,.jpeg">
            <small>Only accepts JPG photo. Maximum of 5 MB.</small><button type="submit">Save Item</button>
        </form>
    </article>
    <article class="content-card"><h2>Menu</h2>
        <?php if (empty($menuItems)): ?><p>No menu items found.</p><?php else: ?>
        <div class="table-wrap"><table><tr><th>ID</th><th>Menu Item</th><th>Category</th><th>Price</th><th>Photo</th><th>Status</th><th>Action</th></tr>
        <?php foreach ($menuItems as $item): ?><tr>
            <td><?php echo e($item['item_id']); ?></td><td><?php echo e($item['item_name']); ?></td><td><?php echo e($item['category']); ?></td><td>₱<?php echo e($item['price']); ?></td>
            <td><?php if (!empty($item['file_name'])): ?><a href="images/<?php echo rawurlencode($item['file_name']); ?>" target="_blank" rel="noopener"><img class="record-image" src="images/<?php echo rawurlencode($item['file_name']); ?>" alt="Photo of <?php echo e($item['item_name']); ?>"></a><?php else: ?>None<?php endif; ?></td>
            <td><?php echo e($item['status']); ?></td><td class="action-cell">
                <form method="post" class="delete-form action-form"><input type="hidden" name="action" value="toggle_status"><input type="hidden" name="item_id" value="<?php echo e($item['item_id']); ?>"><button type="submit" class="action-button action-status">Change status</button></form>
                <a class="btn-delete action-button action-delete" href="menu.php?deleteid=<?php echo rawurlencode($item['item_id']); ?>">Delete</a>
            </td>
        </tr><?php endforeach; ?></table></div>
        <?php endif; ?>
    </article>
</section>
<?php endif; ?>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
