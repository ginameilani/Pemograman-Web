<?php require __DIR__.'/../layouts/header.php'; ?>
<div class="container-fluid">
<h2 class="mb-4">
    Dashboard
</h2>
<div class="row">
    <!-- Total Barang -->
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Total Barang</h5>
                <h2>
                    <?= $total_items ?? 0 ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Total Kategori -->
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Total Kategori</h5>
                <h2>
                    <?= $total_categories ?? 0 ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="col-md-3">
        <div class="card bg-warning">
            <div class="card-body">
                <h5>Total Transaksi</h5>
                <h2>
                    <?= $total_transactions ?? 0 ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Total User -->
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5>Total User</h5>
                <h2>
                    <?= $total_users ?? 0 ?>
                </h2>
            </div>
        </div>
    </div>
</div>
<hr>

<div class="card mt-4">
    <div class="card-header"> Informasi Sistem </div>
    <div class="card-body">
        <p> Selamat datang di Inventory System. </p>
        <p> Gunakan sidebar untuk mengelola barang, kategori, transaksi, dan pengguna. </p>
    </div>
</div>

<?php require __DIR__.'/../layouts/footer.php'; ?>