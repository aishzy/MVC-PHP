<div class="page-header">
    <h1>Users</h1>
    <a href="/users/create" class="btn btn-primary">+ New User</a>
</div>

<?php if (empty($users['data'])): ?>
    <p class="empty">No users yet. <a href="/users/create">Create one</a>.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users['data'] as $user): ?>
            <tr>
                <td><?= e($user['id']) ?></td>
                <td><a href="/users/<?= e($user['id']) ?>"><?= e($user['name']) ?></a></td>
                <td><?= e($user['email']) ?></td>
                <td><?= e($user['created_at']) ?></td>
                <td>
                    <a href="/users/<?= e($user['id']) ?>/edit" class="btn btn-sm">Edit</a>
                    <a href="/users/<?= e($user['id']) ?>/delete"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($users['last_page'] > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $users['last_page']; $i++): ?>
            <a href="/users?page=<?= $i ?>"
               class="<?= $i === $users['current_page'] ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
<?php endif; ?>