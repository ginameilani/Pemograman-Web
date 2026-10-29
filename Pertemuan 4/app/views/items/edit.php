<?php require __DIR__.'/../layouts/header.php'; ?>
<?php
/**
 * @var array $item
 * @var array $categories
 */
?>
  <h3>Edit Barang</h3>
<?php
$action = base_url('items/update/'.$item['id']);
$button = "Update";
require __DIR__.'/_form.php';
?>
<?php require __DIR__.'/../layouts/footer.php'; ?>