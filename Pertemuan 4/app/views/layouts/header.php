<?php $flash = take_flash(); ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title>
  <?= isset($title) ? e($title).' - ' : '' ?>
  <?= APP_NAME ?>
</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"rel="stylesheet">
<style>
  body{
      min-height:100vh;
      background:#f8f9fa;
  }

  /* ini css bagian sidebar */
  .sidebar{
      width:250px;
      height:100vh;
      position:fixed;
      left:0;
      top:0;
      background:#212529;
      color:white;
  }

  .sidebar .brand{
      padding:20px;
      font-size:22px;
      font-weight:bold;
      border-bottom:1px solid #444;
  }
  .sidebar a{
      display:block;
      color:#ddd;
      padding:12px 20px;
      text-decoration:none;
  }
  .sidebar a:hover{
      background:#0d6efd;
      color:white;
  }
  .content{
      margin-left:250px;
      padding:30px;
  }
</style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <div class="brand">Inventory System</div>
    <a href="<?= base_url('/') ?>">Dashboard</a>
    <a href="<?= base_url('items') ?>">Barang</a>
    <a href="<?= base_url('categories') ?>">Kategori</a>
    <a href="<?= base_url('transaction') ?>">Transaksi</a>
    <a href="<?= base_url('users') ?>">Users</a>
    <?php if (!empty($_SESSION['user'])): ?>
        <div class="mt-4 px-3">
            <hr class="border-secondary">
            <div class="text-white-50 small mb-2">
                Login sebagai:
                <?= e($_SESSION['user']['username']) ?>
            </div>
            <form action="<?= base_url('auth/logout') ?>"
                  method="POST">
                <input type="hidden"
                       name="csrf_token"
                       value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                <button type="submit"
                        class="btn btn-danger w-100">
                    Logout
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

  <!-- Content -->
<div class="content">
  <?php if($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
  <?= e($flash['message']) ?>
</div>
<?php endif; ?>
