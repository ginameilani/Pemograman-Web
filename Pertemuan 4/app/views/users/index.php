<?php
$search = $search ?? '';
$role = $role ?? '';
$limit = (int) ($limit ?? 5);
$page = (int) ($page ?? 1);

$users = $users ?? [];
$totalData = (int) ($totalData ?? 0);
$totalPages = (int) ($totalPages ?? 0);
?>

<?php require ROOT_PATH . '/app/views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Data Users</h3>
    </div>
    <!-- ini Form pencarian dan filter -->
    <form method="GET" action="<?= base_url('users') ?>" class="mb-3">
        <div class="row g-2">
            <div class="col-md-5">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari username atau nama..."
                    value="<?= e($search) ?>"
                >
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>
                        Admin
                    </option>
                    <option value="staff" <?= $role === 'staff' ? 'selected' : '' ?>>
                        Staff
                    </option>
                    <option value="manager" <?= $role === 'manager' ? 'selected' : '' ?>>
                        Manager
                    </option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="limit" class="form-select" onchange="this.form.submit()">
                    <?php foreach ([5, 10, 25, 50] as $jumlah): ?>
                        <option value="<?= $jumlah ?>" <?= $limit === $jumlah ? 'selected' : '' ?>>
                            <?= $jumlah ?> Data
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Cari
                </button>
                <a href="<?= base_url('users') ?>" class="btn btn-secondary">
                    Reset
                </a>
            </div>
        </div>
        <a href="<?= base_url('users/create') ?>" class="btn btn-primary mt-3">
            + Tambah User
        </a>
    </form>
    <p class="text-muted">
        Total user ditemukan:
        <strong><?= $totalData ?></strong>
    </p>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Role</th>
                    <th>Tanggal Dibuat</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center">
                            Data user tidak ditemukan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $i => $user): ?>
                        <tr>
                            <td><?= (($page - 1) * $limit) + $i + 1 ?></td>
                            <td><?= e($user['username']) ?></td>
                            <td><?= e($user['full_name'] ?? '-') ?></td>
                            <td>
                                <?php
                                $badge = [
                                    'admin' => 'danger',
                                    'staff' => 'primary',
                                    'manager' => 'success'
                                ][$user['role']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badge ?>">
                                    <?= e(ucfirst($user['role'])) ?>
                                </span>
                            </td>
                            <td><?= e($user['created_at'] ?? '-') ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                    <form method="POST" action="<?= base_url('users/delete/' . $user['id']) ?>" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Navigasi halaman">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <!-- <a class="page-link" href="?<?= http_build_query([
                            'page' => $page - 1,
                            'limit' => $limit,
                            'search' => $search,
                            'role' => $role
                        ]) ?>">
                            Sebelumnya
                        </a> -->
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $page === $i ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query([
                            'page' => $i,
                            'limit' => $limit,
                            'search' => $search,
                            'role' => $role
                        ]) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <!-- <a class="page-link" href="?<?= http_build_query([
                            'page' => $page + 1,
                            'limit' => $limit,
                            'search' => $search,
                            'role' => $role
                        ]) ?>">
                            Berikutnya
                        </a> -->
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>