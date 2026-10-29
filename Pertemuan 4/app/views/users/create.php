
<?php require ROOT_PATH . '/app/views/layouts/header.php'; ?>

<div class="container-fluid">
    <h3 class="mb-4">Tambah User</h3>
    <?php
        $user = [];
        $action = base_url('users/store');
        $button = 'Simpan';
        $isEdit = false;
        require ROOT_PATH . '/app/views/users/_form.php';
    ?>
</div>
<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>