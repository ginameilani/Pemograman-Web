<?php require __DIR__.'/../layouts/header.php'; ?>
<h3>Tambah Transaksi</h3>
<?php
  $action = base_url('transaction/store');
  $button = "Simpan";
  require __DIR__.'/_form.php';
?>
<?php require __DIR__.'/../layouts/footer.php'; ?>