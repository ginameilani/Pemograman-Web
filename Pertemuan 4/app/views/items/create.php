<?php require __DIR__.'/../layouts/header.php'; ?>
  <h3>Tambah Barang</h3>
<?php
$action = base_url('items/store');
$button = "Simpan";
$item = [];
require __DIR__.'/_form.php';
?>
<?php require __DIR__.'/../layouts/footer.php'; ?>