<?php
$action = $action ?? '#';
$button = $button ?? 'Simpan';
$isEdit = $isEdit ?? false;
$user = $user ?? [];
?>
<form method="POST" action="<?= e($action) ?>">
    <div class="mb-3">
        <label for="username" class="form-label">
            Username
        </label>
        <input type="text" id="username" name="username" class="form-control" maxlength="50" value="<?= e($user['username'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label for="full_name" class="form-label">
            Nama Lengkap
        </label>
        <input type="text" id="full_name" name="full_name" class="form-control" maxlength="100" value="<?= e($user['full_name'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">
            Password
        </label>
        <input type="password" id="password" name="password" class="form-control" minlength="8" <?= empty($isEdit) ? 'required' : '' ?> autocomplete="new-password">
        <?php if (!empty($isEdit)): ?>
            <small class="text-muted">
                Kosongkan jika tidak ingin mengganti password.
            </small>
        <?php else: ?>
            <small class="text-muted">
                Password minimal 8 karakter.
            </small>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">
            Role
        </label>
        <select name="role" id="role" class="form-select" required>
            <?php
            $selectedRole = $user['role'] ?? 'staff';
            $roles = [
                'admin' => 'Admin',
                'staff' => 'Staff',
                'manager' => 'Manager'
            ];
            ?>
            <?php foreach ($roles as $value => $label): ?>
                <option value="<?= e($value) ?>" <?= $selectedRole === $value ? 'selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">
        <?= e($button) ?>
    </button>
    <a href="<?= base_url('users') ?>" class="btn btn-secondary">
        Kembali
    </a>
</form>