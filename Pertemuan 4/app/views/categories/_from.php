<?php
/**
 * @var string $action
 * @var string $button
 * @var array $category
 */
?>

<form method="POST" action="<?= $action ?>">
  <div class="mb-3">
    <label> Nama Kategori </label>
    <input type="text" name="name" class="form-control" value="<?= e($category['name'] ?? '') ?>">
  </div>

  <div class="mb-3">
    <label> Deskripsi </label>
    <textarea name="description" class="form-control" rows="3"><?= e($category['description'] ?? '') ?></textarea>
  </div>
  <button class="btn btn-primary"><?= $button ?></button>
  <a href="<?= base_url('categories') ?>" class="btn btn-secondary">
    Kembali
  </a>
</form>