<?php
require ROOT_PATH . '/app/views/layouts/header.php';
?>
<div class="container-fluid">
    <h3 class="mb-4">Tambah Kategori</h3>
    <?php
      $category = [];
      $action = base_url('categories/store');
      $button = "Simpan";
      require '_form.php';
    ?>
</div>
<?php
require ROOT_PATH . '/app/views/layouts/footer.php';
?>