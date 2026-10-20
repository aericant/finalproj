<?php require __DIR__ . '/view_helpers.php'; require __DIR__ . '/../../includes/header.php'; ?>

<?php if ($deleteConfirmationId !== null): ?>
    <section class="content-stack">
        <article class="content-card delete-confirmation">
            <p class="eyebrow">Confirm deletion</p>
            <h2>Delete customer?</h2>
            <p>Do you really want to delete this record with ID of <strong><?php echo e($deleteConfirmationId); ?></strong>?</p>
            <div class="delete-confirmation-actions">
                <a class="action-button action-delete" href="customers.php?yesdelete=<?php echo rawurlencode($deleteConfirmationId); ?>">Yes</a>
                <a class="action-button action-cancel" href="customers.php">No</a>
            </div>
        </article>
    </section>
<?php else: ?>
<section class="content-stack">
    <article class="content-card">
        <p class="eyebrow">Customer Management</p>
        <h2><?php echo $isEditing ? 'Edit Customer' : 'Customer Management'; ?></h2>
        <?php if ($message !== ''): ?><p class="notice"><?php echo e($message); ?></p><?php endif; ?>
        <form method="post" class="employee-form">
            <input type="hidden" name="edit_mode" value="<?php echo $isEditing ? '1' : '0'; ?>">
            <label for="customer_id">Customer ID</label>
            <input id="customer_id" name="customer_id" type="text" value="<?php echo e($newCustomerId); ?>" required>
            <label for="full_name">Customer name</label>
            <input id="full_name" name="full_name" type="text" value="<?php echo e($editCustomer['full_name'] ?? ''); ?>" required>
            <label for="contact_number">Contact number</label>
            <input id="contact_number" name="contact_number" type="tel" value="<?php echo e($editCustomer['contact_number'] ?? ''); ?>">
            <label for="payment_method">Payment method</label>
            <select id="payment_method" name="payment_method">
                <option value="">Select payment method</option>
                <?php foreach (['Cash', 'Card', 'GCash', 'Maya'] as $method): ?>
                    <option value="<?php echo e($method); ?>" <?php echo ($editCustomer['payment_method'] ?? '') === $method ? 'selected' : ''; ?>><?php echo e($method); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit"><?php echo $isEditing ? 'Update Customer' : 'Save Customer'; ?></button>
            <?php if ($isEditing): ?><a class="btn" href="customers.php">Cancel</a><?php endif; ?>
        </form>
    </article>
    <article class="content-card">
        <h2>Customers</h2>
        <?php if (empty($customers)): ?><p>No customers found.</p><?php else: ?>
            <div class="table-wrap"><table><tr><th>ID</th><th>Name</th><th>Contact</th><th>Payment method</th><th>Action</th></tr>
            <?php foreach ($customers as $customer): ?><tr>
                <td><?php echo e($customer['customer_id']); ?></td><td><?php echo e($customer['full_name']); ?></td>
                <td><?php echo e($customer['contact_number']); ?></td><td><?php echo e($customer['payment_method']); ?></td>
                <td class="customer-actions action-cell">
                    <a class="btn action-button action-edit" href="customers.php?action=edit&amp;customer_id=<?php echo rawurlencode($customer['customer_id']); ?>">Edit</a>
                    <a class="btn-delete action-button action-delete" href="customers.php?deleteid=<?php echo rawurlencode($customer['customer_id']); ?>">Delete</a>
                </td>
            </tr><?php endforeach; ?></table></div>
        <?php endif; ?>
    </article>
</section>
<?php endif; ?>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
