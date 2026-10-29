<?php
/**
 * @var array $category
 */
require ROOT_PATH . '/app/views/layouts/header.php';
?>
<div class="container-fluid">
  <h2>Edit Kategori</h2>
  <?php
    $action = base_url(
        'categories/update/'.$category['id']
    );
    $button = "Update";
    require ROOT_PATH . '/app/views/categories/_form.php';
  ?>
</div>
<?php
require ROOT_PATH . '/app/views/layouts/footer.php';
?>