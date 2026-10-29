<?php require __DIR__.'/../layouts/header.php'; ?>
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
    <h3> Data Kategori </h3>
  </div>
  <form method="GET" class="mb-3">
  <div class="row">
      <div class="col-md-6">
          <input 
              type="text" 
              name="search" 
              class="form-control"
              placeholder="Cari kategori..."
              value="<?= $search ?>"
          >
      </div>
      <div class="col-md-3">
          <select name="limit" class="form-control" onchange="this.form.submit()">
              <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
              <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
              <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
          </select>
      </div>
      <div class="col-md-3">
          <button class="btn btn-primary">
              Cari
          </button>
      </div>
    </div>
    <a href="<?= base_url('categories/create') ?>" class="btn btn-primary mt-2">
        + Tambah Kategori
    </a>
</form>

<!-- komentar PHPDoc untuk memberi informasi kepada editor (VS Code/Intelephense) bahwa variabel $categories memang ada dan bertipe array. -->
<?php
/**
 * @var array $categories
 */

?>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th width="60"> No </th>
        <th> Nama Kategori </th>
        <th> Deskripsi </th>
        <th width="160"> Aksi </th>
      </tr>
    </thead>
  <tbody>
    <?php foreach($categories as $i=>$category): ?>
      <tr>
        <td> <?= $i+1 ?> </td>
        <td> <?= e($category['name']) ?> </td>
        <td> <?= e($category['description'] ?? '-') ?> </td>
        <td>
          <div class="d-flex gap-2">
            <a href="<?= base_url('categories/edit/'.$category['id']) ?>" class="btn btn-warning btn-sm">
            Edit
            </a>

            <a href="<?= base_url('categories/delete/'.$category['id']) ?>" class="btn btn-danger btn-sm"
            onclick="return confirm('Apakah yakin ingin menghapus kategori ini?')">
            Hapus
            </a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<nav class="mt-3">
    <ul class="pagination">

        <!-- Previous -->
        <?php if($page > 1): ?>
        <li class="page-item">
            <a class="page-link" 
               href="?page=<?= $page-1 ?>&limit=<?= $limit ?>&search=<?= $search ?>">
                Previous
            </a>
        </li>
        <?php endif; ?>


        <!-- Nomor halaman -->
        <?php for($i = 1; $i <= $totalPages; $i++): ?>

        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
            <a class="page-link"
               href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= $search ?>">
                <?= $i ?>
            </a>
        </li>

        <?php endfor; ?>


        <!-- Next -->
        <?php if($page < $totalPages): ?>
        <li class="page-item">
            <a class="page-link"
               href="?page=<?= $page+1 ?>&limit=<?= $limit ?>&search=<?= $search ?>">
                Next
            </a>
        </li>
        <?php endif; ?>

    </ul>
</nav>
<?php require __DIR__.'/../layouts/footer.php'; ?>