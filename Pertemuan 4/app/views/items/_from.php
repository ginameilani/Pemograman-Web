  <?php
  /**
   * @var string $action
   * @var string $button
   * @var array $categories
   * @var array $item
   */
  ?>
  <form method="POST" action="<?= $action ?>">
    <div class="mb-3">
      <label>Nama Barang</label>
      <input type="text" name="name" class="form-control" value="<?= e($item['name'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label>Kategori</label>
      <select name="category_id" class="form-control">
        <option value=""> -- Pilih Kategori -- </option>
          <?php foreach($categories as $category): ?>
        <option value="<?= $category['id'] ?>" <?= isset($item['category_id']) && $item['category_id']==$category['id'] ? 'selected' : '' ?>>
          <?= e($category['name']) ?>
        </option>
        <?php endforeach; ?>
      </select>
      </div>
      <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" name="quantity" class="form-control" value="<?= e($item['quantity'] ?? 0) ?>">
      </div>
      <div class="mb-3">
        <label>Satuan</label>
        <input type="text" name="unit" class="form-control" value="<?= e($item['unit'] ?? 'unit') ?>">
    </div>
    <div class="mb-3">
      <label>Harga</label>
      <input type="number" name="price" class="form-control" value="<?= e($item['price'] ?? 0) ?>">
    </div>
    <div class="mb-3">
      <label>Tanggal Masuk</label>
      <input type="date" name="entry_date" class="form-control" value="<?= e($item['entry_date'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="description" class="form-control" rows="3"><?= e($item['description'] ?? '') ?></textarea>
    </div>
    <button class="btn btn-primary"><?= $button ?></button>
    <a href="<?= base_url('items') ?>" class="btn btn-secondary"> Kembali </a>
  </form>