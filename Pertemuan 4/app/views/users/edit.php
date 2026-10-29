
<?php
require_once ROOT_PATH . '/app/views/layouts/header.php';
$user = $user ?? null;
if (!$user) {
    echo '<div class="alert alert-danger">Data user tidak ditemukan.</div>';
} else {
    $action = base_url('users/update/' . $user['id']);
    $button = 'Update';
    $isEdit = true;
?>
<div class="container-fluid">
    <h3 class="mb-4">Edit User</h3>
    <?php require ROOT_PATH . '/app/views/users/_form.php'; ?>
</div>
<?php
}
require_once ROOT_PATH . '/app/views/layouts/footer.php';
?>