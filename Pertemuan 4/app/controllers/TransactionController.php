<?php
class TransactionController extends Controller
{
    // halaman daftar transaksi
   
public function index(): void
{
    $search = trim($_GET['search'] ?? '');
    $type = $_GET['type'] ?? '';
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $allowedLimits = [5, 10, 25];
    $limit = (int) ($_GET['limit'] ?? 5);
    if (!in_array($limit, $allowedLimits, true)) {
        $limit = 5;
    }
    $result = $this->model('Transaction')->paginate(
        $search,
        $type,
        $limit,
        $page
    );
    $totalPages = $result['total_pages'];
    if ($totalPages > 0 && $page > $totalPages) {
        $page = $totalPages;
        $result = $this->model('Transaction')->paginate(
            $search,
            $type,
            $limit,
            $page
        );
    }
    $this->view('transaction/index', [
        'title' => 'Data Transaksi',
        'transactions' => $result['data'],
        'totalData' => $result['total_data'],
        'totalPages' => max(1, $result['total_pages']),
        'page' => $page,
        'limit' => $limit,
        'search' => $search,
        'type' => $type
    ]);
}

    // halaman tambah transaksi
    public function create(): void
      {
          $this->view('transaction/create', [
              'title' => 'Tambah Transaksi',
              'items' => $this->model('Item')->all()
          ]);
      }

    // simpan transaksi
    // simpan transaksi
public function store(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('transaction');
    }
    $input = array_map('trim', $_POST);
    // cek stok jika barang keluar
    if($input['type'] == 'OUT'){
        $item = $this->model('Item')->find($input['item_id']);
        if($item['quantity'] < $input['quantity']){
            $this->flash(
                'danger',
                'Stok tidak cukup. Stok tersedia: '.$item['quantity']
            );
            $this->redirect('transaction/create');
        }
    }
    // simpan transaksi
    $result = $this->model('Transaction')->create([
        'item_id' => $input['item_id'],
        'user_id' => 1,
        'type' => $input['type'],
        'quantity' => $input['quantity'],
        'notes' => $input['notes'] ?? ''
    ]);
    // jika transaksi berhasil, update stok
    if($result){
        $this->model('Item')->updateStock(
            $input['item_id'],
            $input['quantity'],
            $input['type']
        );
        $this->flash(
            'success',
            'Transaksi berhasil disimpan'
        );
    }else{
        $this->flash(
            'danger',
            'Transaksi gagal disimpan'
        );
    }
    $this->redirect('transaction');
}

// hapus data
public function delete(int $id): void
{
    $transaction = $this->model('Transaction')->find($id);
    if($transaction){
        $itemModel = $this->model('Item');
        if($transaction['type'] == 'IN'){
            // hapus transaksi masuk = stok dikurangi
            $itemModel->updateStock(
                $transaction['item_id'],
                $transaction['quantity'],
                'OUT'
            );
        }else{
            // hapus transaksi keluar = stok dikembalikan
            $itemModel->updateStock(
                $transaction['item_id'],
                $transaction['quantity'],
                'IN'
            );
        }
        $this->model('Transaction')->delete($id);
    }
    $this->flash(
        'success',
        'Transaksi berhasil dihapus'
    );
    $this->redirect('transaction');
}

// pindah halaman ke form editt
public function edit(int $id): void
{
    $this->view(
        'transaction/edit',
        [
            'title'=>'Edit Transaksi',
            'transaction'=>$this->model('Transaction')->find($id),
            'items'=>$this->model('Item')->all()
        ]
    );
}

// fungsi untuk update data ini
public function update(int $id): void
{
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        $this->redirect('transaction');
    }

    $input = array_map('trim', $_POST);

    $transactionModel = $this->model('Transaction');
    $itemModel = $this->model('Item');
    // transaksi lama
    $old = $transactionModel->find($id);
    /*
        Kembalikan stok transaksi lama
    */
    if($old['type'] == 'IN'){
        // hapus efek masuk
        $itemModel->updateStock(
            $old['item_id'],
            $old['quantity'],
            'OUT'
        );
    }else{
        // hapus efek keluar
        $itemModel->updateStock(
            $old['item_id'],
            $old['quantity'],
            'IN'
        );
    }
    if($input['type'] == 'IN'){

        $itemModel->updateStock(
            $input['item_id'],
            $input['quantity'],
            'IN'
        );
    }else{
        // cek stok cukup atau engga
        $item = $itemModel->find($input['item_id']);
        if($item['quantity'] < $input['quantity']){
            $this->flash(
                'error',
                'Stok tidak mencukupi'
            );
            $this->redirect('transaction');
        }
        $itemModel->updateStock(
            $input['item_id'],
            $input['quantity'],
            'OUT'
        );
    }

    // update data transaksi
    $transactionModel->update(
        $id,
        [
            'item_id'=>$input['item_id'],
            'type'=>$input['type'],
            'quantity'=>$input['quantity'],
            'notes'=>$input['notes']
        ]
    );


    $this->flash(
        'success',
        'Transaksi berhasil diperbarui'
    );


    $this->redirect('transaction');
}
}