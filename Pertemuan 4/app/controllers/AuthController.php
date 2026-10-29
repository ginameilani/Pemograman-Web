
<?php

class AuthController extends Controller
{
    // Menampilkan halaman login.
    public function login(): void
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect('');
        }
        $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
        $this->view('auth/login', [
            'title' => 'Login'
        ]);
    }

    // Memproses login.
    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/login');
        }
        $token = $_POST['csrf_token'] ?? '';
        if (
            !is_string($token) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $token)
        ) {
            $this->flash('danger', 'Permintaan tidak valid. Silakan coba lagi.');
            $this->redirect('auth/login');
        }
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($username === '' || $password === '') {
            $this->flash('danger', 'Username dan password wajib diisi.');
            $this->redirect('auth/login');
        }
        $user = $this->model('User')->findByUsername($username);
        if (!$user || !password_verify($password, $user['password'])) {
            $this->flash('danger', 'Username atau password salah.');
            $this->redirect('auth/login');
        }
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'] ?? '',
            'role' => $user['role']
        ];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $this->flash(
            'success',
            'Selamat datang, ' . ($user['full_name'] ?: $user['username']) . '!'
        );
        $this->redirect('');
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('');
        }
        $token = $_POST['csrf_token'] ?? '';
        if (
            !is_string($token) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $token)
        ) {
            $this->flash('danger', 'Permintaan logout tidak valid.');
            $this->redirect('');
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                [
                    'expires' => time() - 42000,
                    'path' => $params['path'],
                    'domain' => $params['domain'],
                    'secure' => $params['secure'],
                    'httponly' => $params['httponly'],
                    'samesite' => $params['samesite']
                ]
            );
        }
        session_destroy();
        $this->redirect('auth/login');
    }
}