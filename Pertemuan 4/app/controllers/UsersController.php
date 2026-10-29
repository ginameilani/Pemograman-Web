
<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class UsersController extends Controller
{
    // Menampilkan daftar user
    public function index(): void
    {
        $search = trim($_GET['search'] ?? '');
        $role = $_GET['role'] ?? '';
        $limit = (int) ($_GET['limit'] ?? 5);
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $result = $this->model('User')->paginate(
            $search,
            $role,
            $limit,
            $page
        );
        // kalai halaman yang diminta sudah melebihi jumlah halaman
        if ($result['total_pages'] > 0 &&
            $page > $result['total_pages']) {
            $this->redirect(
                'users?' . http_build_query([
                    'search' => $search,
                    'role' => $role,
                    'limit' => $result['limit'],
                    'page' => $result['total_pages']
                ])
            );
        }

        $this->view('users/index', [
            'title' => 'Data Users',
            'users' => $result['data'],
            'totalData' => $result['total_data'],
            'totalPages' => $result['total_pages'],
            'page' => $result['page'],
            'limit' => $result['limit'],
            'search' => $search,
            'role' => $role
        ]);
    }

    // Menampilkan form tambah user
    public function create(): void
    {
        $this->view('users/create', [
            'title' => 'Tambah User'
        ]);
    }

    // Menyimpan user baru
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('users');
        }
        $username = trim($_POST['username'] ?? '');
        $fullName = trim($_POST['full_name'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'staff';
        if (
            $username === '' ||
            $fullName === '' ||
            $password === ''
        ) {
            $this->flash('danger', 'Semua kolom wajib diisi.');
            $this->redirect('users/create');
        }
        if (strlen($username) > 50 || strlen($fullName) > 100) {
            $this->flash('danger', 'Username maksimal 50 karakter dan nama maksimal 100 karakter.');
            $this->redirect('users/create');
        }
        if (strlen($password) < 8) {
            $this->flash('danger', 'Password minimal 8 karakter.');
            $this->redirect('users/create');
        }
        if (!in_array($role, ['admin', 'staff', 'manager'], true)) {
            $this->flash('danger', 'Role tidak valid.');
            $this->redirect('users/create');
        }
        try {
            $this->model('User')->create([
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'full_name' => $fullName,
                'role' => $role
            ]);
            $this->flash('success', 'User berhasil ditambahkan.');
            $this->redirect('users');
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->flash('danger', 'Username sudah digunakan.');
                $this->redirect('users/create');
            }
            throw $e;
        }
    }

    // Menampilkan form edit
    public function edit(int $id): void
    {
        $user = $this->model('User')->find($id);
        if (!$user) {
            $this->flash('danger', 'User tidak ditemukan.');
            $this->redirect('users');
        }
        $this->view('users/edit', [
            'title' => 'Edit User',
            'user' => $user
        ]);
    }

    // update perubahan user
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('users');
        }
        $model = $this->model('User');
        $user = $model->find($id);
        if (!$user) {
            $this->flash('danger', 'User tidak ditemukan.');
            $this->redirect('users');
        }
        $username = trim($_POST['username'] ?? '');
        $fullName = trim($_POST['full_name'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        if ($username === '' || $fullName === '') {
            $this->flash('danger', 'Username dan nama wajib diisi.');
            $this->redirect('users/edit/' . $id);
        }
        if (strlen($username) > 50 || strlen($fullName) > 100) {
            $this->flash('danger', 'Username atau nama terlalu panjang.');
            $this->redirect('users/edit/' . $id);
        }
        if ($password !== '' && strlen($password) < 8) {
            $this->flash('danger', 'Password baru minimal 8 karakter.');
            $this->redirect('users/edit/' . $id);
        }
        if (!in_array($role, ['admin', 'staff', 'manager'], true)) {
            $this->flash('danger', 'Role tidak valid.');
            $this->redirect('users/edit/' . $id);
        }
        $data = [
            'username' => $username,
            'full_name' => $fullName,
            'role' => $role,
            'password' => $password !== ''
                ? password_hash($password, PASSWORD_DEFAULT)
                : ''
        ];
        try {
            $model->update($id, $data);
            $this->flash('success', 'Data user berhasil diperbarui.');
            $this->redirect('users');
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->flash('danger', 'Username sudah digunakan.');
                $this->redirect('users/edit/' . $id);
            }
            throw $e;
        }
    }

    // Menghapus user
    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('users');
        }
        $model = $this->model('User');
        $user = $model->find($id);
        if (!$user) {
            $this->flash('danger', 'User tidak ditemukan.');
            $this->redirect('users');
        }
        if ($model->countTransactions($id) > 0) {
            $this->flash(
                'danger',
                'User tidak bisa dihapus karena masih memiliki riwayat transaksi.'
            );
            $this->redirect('users');
        }
        $model->remove($id);
        $this->flash('success', 'User berhasil dihapus.');
        $this->redirect('users');
    }
}