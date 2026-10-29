<?php
/**
 * @var string $action
 * @var string $button
 * @var array $categories
 * @var array $items
 */
?>
<form method="POST" action="<?= $action ?>">
  <div class="mb-3">
      <div class="mb-3">
        <label>Barang</label>
          <select name="item_id" id="item_id" class="form-control">
            <option value="">-- Pilih Barang --</option>
              <?php foreach($items as $i): ?>
            <option value="<?= $i['id'] ?>" data-code="<?= $i['code'] ?>" data-stock="<?= $i['quantity'] ?>">
              <?= e($i['name']) ?>
            </option>
              <?php endforeach; ?>
    </select>
  </div>

<div class="alert alert-info">
  Kode Barang :
  <b id="item_code">-</b>
  <br>
  Stok Tersedia :
  <b id="item_stock">-</b>
</div>

  <div class="mb-3">
    <label>Tipe Transaksi</label>
    <select name="type" class="form-control">
      <option value="IN"
        <?= isset($transaction) && $transaction['type']=='IN'?'selected':'' ?>>
        Barang Masuk
        </option>
        <option value="OUT"
        <?= isset($transaction) && $transaction['type']=='OUT'?'selected':'' ?>>
        Barang Keluar
      </option>
    </select>
  </div>

  <div class="mb-3">
    <label>Jumlah</label>
    <input type="number" name="quantity" class="form-control" value="<?= $transaction['quantity'] ?? '' ?>" required>
  </div>

  <div class="mb-3">
    <label>Keterangan</label>
    <textarea name="notes" class="form-control"><?= $transaction['notes'] ?? ''?></textarea>
  </div>
  <button class="btn btn-primary"><?= $button ?></button>
  <a href="<?=base_url('transaction')?>" class="btn btn-secondary"> Kembali</a>
</form>

<script>
const itemSelect = document.getElementById('item_id');
const stockText = document.getElementById('item_stock');
const codeText = document.getElementById('item_code');
itemSelect.addEventListener('change', function(){
    let option = this.options[this.selectedIndex];
    codeText.innerHTML = option.dataset.code ?? '-';
    stockText.innerHTML = option.dataset.stock ?? '-';
});
</script>