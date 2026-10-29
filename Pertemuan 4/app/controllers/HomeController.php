
<?php

class HomeController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance()->pdo();

        $total_items = (int) $db
            ->query("SELECT COUNT(*) FROM items")
            ->fetchColumn();

        $total_categories = (int) $db
            ->query("SELECT COUNT(*) FROM categories")
            ->fetchColumn();

        $total_transactions = (int) $db
            ->query("SELECT COUNT(*) FROM transactions")
            ->fetchColumn();

        $total_users = (int) $db
            ->query("SELECT COUNT(*) FROM users")
            ->fetchColumn();

        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'total_items' => $total_items,
            'total_categories' => $total_categories,
            'total_transactions' => $total_transactions,
            'total_users' => $total_users
        ]);
    }
}