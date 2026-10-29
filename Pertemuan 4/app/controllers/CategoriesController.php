<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Category.php';
/** @noinspection PhpUndefinedClassInspection */
class CategoriesController extends Controller
{
    // menampilkan data
   public function index()
    {
        $categoryModel = $this->model('Category');
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;
        $search = $_GET['search'] ?? '';
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;
        $categories = $categoryModel->paginate(
            $limit,
            $offset,
            $search
        );
        $total = $categoryModel->count($search);
        $totalPages = ceil($total / $limit);
        $this->view(
            'categories/index',
            [
                'title' => 'Data Kategori',
                'categories' => $categories,
                'page' => $page,
                'limit' => $limit,
                'search' => $search,
                'totalPages' => $totalPages
            ]
        );
    }

    // ketika di klik tombol tambah maka akan pindah ke form tambah data kategori 
    public function create()
    {
        $this->view(
            'categories/create',
            [
                'title'=>'Tambah Kategori'
            ]
        );
    }

    // proses tambah data 
    public function store()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            $this->redirect('categories');
        }
        $data=[
            'name'=>trim($_POST['name']),
            'description'=>trim($_POST['description'] ?? '')
        ];
        if(empty($data['name']))
        {
            $this->flash(
                'danger',
                'Nama kategori wajib diisi'
            );
            $this->redirect(
                'categories/create'
            );
        }
        $this->model('Category')
             ->create($data);
        $this->flash(
            'success',
            'Kategori berhasil ditambahkan'
        );
        $this->redirect(
            'categories'
        );
    }

    // ketika di klik tombol edit akan pindah ke form edit 
    public function edit(int $id)
    {
        $category =
        $this->model('Category')
             ->find($id);
        $this->view(
            'categories/edit',
            [
                'title'=>'Edit Kategori',
                'category'=>$category
            ]
        );
    }

    // proses edit 
    public function update(int $id)
    {
        $data=[
            'name'=>trim($_POST['name']),
            'description'=>trim($_POST['description'] ?? '')
        ];
        $this->model('Category')
             ->update($id,$data);
        $this->redirect(
            'categories'
        );
    }

    // hapus data katgori
   public function delete(int $id)
    {
        $categoryModel = new Category();
        $totalItems = $categoryModel->countItems($id);
        if($totalItems > 0)
        {
            echo "
            <script>
            alert('Kategori masih digunakan oleh barang!');
            window.location.href='".base_url('categories')."';
            </script>";
            exit;
        }
        $categoryModel->remove($id);
        header(
            "Location: ".base_url('categories')
        );
        exit;
    }
}