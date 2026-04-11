<div class="card">
    <h1><?= e($user['name']) ?></h1>
    <dl>
        <dt>Email</dt>  <dd><?= e($user['email']) ?></dd>
        <dt>ID</dt>     <dd><?= e($user['id']) ?></dd>
        <dt>Created</dt><dd><?= e($user['created_at']) ?></dd>
    </dl>
    <div class="actions">
        <a href="/users/<?= e($user['id']) ?>/edit" class="btn btn-primary">Edit</a>
        <a href="/users" class="btn">Back to list</a>
    </div>
</div>