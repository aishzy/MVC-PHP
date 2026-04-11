<div class="form-card">
    <h1><?= e($title) ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $fieldErrors): ?>
                    <?php foreach ($fieldErrors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php
        $isEdit  = !empty($user['id']);
        $action  = $isEdit ? '/users/' . e($user['id']) : '/users';
    ?>
    <form method="POST" action="<?= $action ?>">
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name"
                   value="<?= e($old['name'] ?? $user['name'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="<?= e($old['email'] ?? $user['email'] ?? '') ?>"
                   required>
        </div>

        <?php if (!$isEdit): ?>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   minlength="8" required>
        </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Update User' : 'Create User' ?>
            </button>
            <a href="/users" class="btn">Cancel</a>
        </div>
    </form>
</div>