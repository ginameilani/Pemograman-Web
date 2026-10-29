<?php

class ItemsController extends Controller
{
    // halaman daftar barang
    public function index(): void
{
    $page = isset($_GET['page'])
        ? (int)$_GET['page']
        : 1;
    $limit = isset($_GET['limit'])
        ? (int)$_GET['limit']
        : 5;
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';
    if ($page < 1) {
        $page = 1;
    }
    $offset = ($page - 1) * $limit;
    $itemModel = $this->model('Item');
    $items = $itemModel->paginate(
        $limit,
        $offset,
        $search,
        $category
    );
    $total = $itemModel->count(
        $search,
        $category
    );
    $totalPages = ceil($total / $limit);
    $this->view('items/index', [
        'title' => 'Data Barang',
        'items' => $items,
        'page' => $page,
        'limit' => $limit,
        'search' => $search,
        'totalPages' => $totalPages,
        'categories' => $this->model('Category')->all()
    ]);
}

    // halaman tambah barang
    public function create(): void
    {
        $this->view('items/create', [
            'title' => 'Tambah Barang',
            'categories' => $this->model('Category')->all()
        ]);
    }

    // simpan barang baru
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('items');
        }
        $input = array_map('trim', $_POST);
        $this->model('Item')->create([
            'code' => $this->model('Item')->generateCode(),
            'name' => $input['name'],
            'category_id' => 
                !empty($input['category_id'])
                ? $input['category_id']
                : null,
            'quantity' => $input['quantity'] ?? 0,
            'unit' => $input['unit'] ?? 'unit',
            'price' => (float)($input['price'] ?? 0),
            'entry_date' => !empty($input['entry_date'])
                ? $input['entry_date']
                : null,
            'description' =>$input['description'] ?? ''
        ]);
        $this->flash(
            'success',
            'Barang berhasil ditambahkan'
        );
        $this->redirect('items');
    }

    // halaman edit barang
    public function edit(int $id): void
    {
        $this->view('items/edit',[
            'title'=>'Edit Barang',
            'item'=>$this->model('Item')->find($id),
            'categories'=>$this->model('Category')->all()
        ]);
    }

    // update barang
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('items');
        }
        $input=array_map('trim',$_POST);
        $this->model('Item')->update($id,[
            'code'=>$input['code'],
            'name'=>$input['name'],
            'category_id'=>
                !empty($input['category_id'])
                ? $input['category_id']
                : null,
            'quantity'=>$input['quantity'],
            'unit'=>$input['unit'],
            'price'=>(float)$input['price'],
            'entry_date'=>
                $input['entry_date'] ?? null,
            'description'=>
                $input['description'] ?? ''
        ]);
        $this->flash(
            'success',
            'Barang berhasil diperbarui'
        );
        $this->redirect('items');
    }

    // hapus barang
    public function delete(int $id): void
    {
        $this->model('Item')->delete($id);
        $this->flash(
            'success',
            'Barang berhasil dihapus'
        );
        $this->redirect('items');
    }
}