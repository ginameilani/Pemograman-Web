<?php require __DIR__.'/../layouts/header.php'; ?>
<?php
/**
 * @var array $transaction
 */
?>
<h3>Edit Transaksi</h3>
<?php
  $action = base_url(
  'transaction/update/'.$transaction['id']
  );
  $button = "Update";
  require __DIR__.'/_form.php';
?>
<?php require __DIR__.'/../layouts/footer.php'; ?>