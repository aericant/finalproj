<?php require __DIR__ . '/view_helpers.php'; require __DIR__ . '/../../includes/header.php'; ?>

<?php if ($deleteConfirmationId !== null): ?>
    <section class="content-stack">
        <article class="content-card delete-confirmation">
            <p class="eyebrow">Confirm deletion</p><h2>Delete employee?</h2>
            <p>Do you really want to delete this record with ID of <strong><?php echo e($deleteConfirmationId); ?></strong>?</p>
            <div class="delete-confirmation-actions">
                <a class="action-button action-delete" href="employees.php?yesdelete=<?php echo rawurlencode($deleteConfirmationId); ?>">Yes</a>
                <a class="action-button action-cancel" href="employees.php">No</a>
            </div>
        </article>
    </section>
<?php else: ?>
<section class="content-stack">
    <article class="content-card employee-layout">
        <div><p class="eyebrow">Team roster</p><h2>Employees</h2><p class="section-copy">Keep each shift ready with the people and photos behind the counter.</p></div>
        <?php if ($message !== ''): ?><p class="notice"><?php echo e($message); ?></p><?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="employee-form">
            <label for="employee_id">Employee ID</label><input id="employee_id" name="employee_id" type="text" value="<?php echo e($newEmployeeId); ?>" required>
            <label for="full_name">Employee name</label><input id="full_name" name="full_name" type="text" required>
            <label for="role">Role</label><input id="role" name="role" type="text" placeholder="Barista" required>
            <label for="department">Department</label><input id="department" name="department" type="text" placeholder="Front counter" required>
            <label for="shift">Shift time</label>
            <select id="shift" name="shift" required><option value="">Select shift</option><option>Morning Shift</option><option>Afternoon Shift</option></select>
            <label for="status">Employment status</label>
            <select id="status" name="status" required><option>Active</option><option>On Leave</option></select>
            <label for="employee_file">Employee JPG photo</label><input id="employee_file" name="employee_file" type="file" accept="image/jpeg,.jpg,.jpeg">
            <small>Only accepts JPG photo. Maximum of 5 MB.</small><button type="submit">Add Employee</button>
        </form>
    </article>
    <article class="content-card">
        <h2>Current roster</h2>
        <?php if (empty($employees)): ?><p>No employees added yet.</p><?php else: ?>
        <div class="table-wrap"><table><tr><th>Employee ID</th><th>Name</th><th>Role</th><th>Department</th><th>Shift</th><th>Photo</th><th>Status</th><th>Action</th></tr>
        <?php foreach ($employees as $employee): ?><tr>
            <td><?php echo e($employee['employee_id']); ?></td><td><?php echo e($employee['full_name']); ?></td>
            <td><?php echo e($employee['role']); ?></td><td><?php echo e($employee['department']); ?></td><td><?php echo e($employee['shift']); ?></td>
            <td><?php if (!empty($employee['file_name'])): ?><a href="images/<?php echo rawurlencode($employee['file_name']); ?>" target="_blank" rel="noopener"><img class="record-image" src="images/<?php echo rawurlencode($employee['file_name']); ?>" alt="Photo of <?php echo e($employee['full_name']); ?>"></a><?php else: ?>None<?php endif; ?></td>
            <td><?php echo e($employee['status']); ?></td><td class="employee-actions action-cell">
                <form method="post" class="status-form action-form"><input type="hidden" name="action" value="toggle_status"><input type="hidden" name="employee_id" value="<?php echo e($employee['employee_id']); ?>"><button type="submit" class="action-button action-status">Change status</button></form>
                <a class="btn-delete action-button action-delete" href="employees.php?deleteid=<?php echo rawurlencode($employee['employee_id']); ?>">Delete</a>
            </td>
        </tr><?php endforeach; ?></table></div>
        <?php endif; ?>
    </article>
</section>
<?php endif; ?>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
