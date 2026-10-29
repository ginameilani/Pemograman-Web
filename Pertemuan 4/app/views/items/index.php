<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php
/**
 * @var array $categories
 * @var int $page
 * @var int $limit
 * @var string $search
 * @var int $totalPages
 */
?>
<div class="d-flex justify-content-between mb-3">
    <h3>Data Barang</h3>
</div>
<form method="GET" class="mb-3">
  <div class="row">
      <div class="col-md-5">
          <input type="text" name="search" class="form-control" placeholder="Cari kode atau nama barang..." value="<?= $search ?? '' ?>">
      </div>
      <div class="col-md-3">
          <select name="category" class="form-control" onchange="this.form.submit()">
              <option value=""> Semua Kategori </option>
              <?php foreach($categories as $category): ?>
              <option 
                  value="<?= $category['id'] ?>"
                  <?= ($category['id'] == ($category ?? '')) ? 'selected' : '' ?>>
                  <?= e($category['name']) ?>
              </option>
              <?php endforeach; ?>
          </select>
      </div>
      <div class="col-md-2">
          <select name="limit" class="form-control" onchange="this.form.submit()">
              <option value="5" <?= $limit == 5 ? 'selected':'' ?>> 5 </option>
              <option value="10" <?= $limit == 10 ? 'selected':'' ?>> 10 </option>
              <option value="25" <?= $limit == 25 ? 'selected':'' ?>> 25 </option>
          </select>
      </div>
      <div class="col-md-2">
          <button class="btn btn-primary"> Cari </button>
      </div>
  </div>
   <a href="<?= base_url('items/create') ?>" class="btn btn-primary mt-2">
        + Tambah Barang
    </a>
</form>

<!-- table data items -->
<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Nama Barang</th>
        <th>Kategori</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Harga</th>
        <th>Tanggal Masuk</th>
        <th>Deksripsi</th>
        <th>Aksi</th>
    </tr>
  </thead>

  <tbody>
  <?php if(empty($items)): ?>
    <tr>
      <td colspan="9" class="text-center">
        Belum ada data barang
      </td>
    </tr>
  <?php else: ?>
      <?php foreach($items as $i => $item): ?>
        <tr>
          <td> <?= $i + 1 ?> </td>
          <td> <?= e($item['code']) ?> </td>
          <td> <?= e($item['name']) ?> </td>
          <td> <?= e($item['category_name'] ?? '-') ?> </td>
          <td> <?= e($item['quantity']) ?> </td>
          <td> <?= e($item['unit']) ?> </td>
          <td> <?= rupiah($item['price']) ?> </td>
          <td> <?= e($item['entry_date']) ?> </td>
          <td> <?= e($item['description'] ?? '-') ?> </td>
          <!-- tombol edit dan hapus -->
          <td>
            <div class="d-flex gap-2">
                <a href="<?= base_url('items/edit/'.$item['id']) ?>" class="btn btn-warning btn-sm">
                    Edit
                </a>
                <a href="<?= base_url('items/delete/'.$item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('kamu yakin ingin Menghapus barang ini?')">
                    Hapus
                </a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<nav>
  <ul class="pagination">
    <?php for($i=1; $i <= $totalPages; $i++): ?>
    <li class="page-item <?= $page == $i ? 'active':'' ?>">
      <a class="page-link" href="<?= base_url('items?page='.$i.'&limit='.$limit.'&search='.$search) ?>">
      <?= $i ?>
      </a>
    </li>
    <?php endfor; ?>
  </ul>
</nav>
<?php require __DIR__ . '/../layouts/footer.php'; ?>