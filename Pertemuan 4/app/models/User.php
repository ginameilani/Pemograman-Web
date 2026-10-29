
<?php

require_once ROOT_PATH . '/app/core/Model.php';

class User extends Model
{
    protected string $table = 'users';
    public function paginate(
        string $search,
        string $role,
        int $limit,
        int $page
    ): array {
        $allowedLimits = [5, 10, 25, 50];
        if (!in_array($limit, $allowedLimits, true)) {
            $limit = 5;
        }
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;
        $where = [];
        $params = [];
        if ($search !== '') {
            $where[] = "(
                username LIKE :username
                OR full_name LIKE :full_name
            )";
            $keyword = '%' . $search . '%';
            $params['username'] = $keyword;
            $params['full_name'] = $keyword;
        }
        if (in_array($role, ['admin', 'staff', 'manager'], true)) {
            $where[] = "role = :role";
            $params['role'] = $role;
        }
        $whereSql = $where
            ? ' WHERE ' . implode(' AND ', $where)
            : '';
        $countSql = "SELECT COUNT(*) FROM users $whereSql";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $totalData = (int) $countStmt->fetchColumn();
        $sql = "SELECT id, username, full_name, role, created_at
                FROM users
                $whereSql
                ORDER BY id ASC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return [
            'data' => $stmt->fetchAll(),
            'total_data' => $totalData,
            'total_pages' => (int) ceil($totalData / $limit),
            'page' => $page,
            'limit' => $limit
        ];
    }    public function find(int $id)
    {
        $stmt = $this->db->prepare(
            "SELECT id, username, full_name, role, created_at
             FROM users
             WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Menambahkan user 
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users
                (username, password, full_name, role)
             VALUES
                (:username, :password, :full_name, :role)"
        );
        return $stmt->execute($data);
    }
    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        if (!empty($data['password'])) {
            $sql = "UPDATE users SET
                        username = :username,
                        password = :password,
                        full_name = :full_name,
                        role = :role
                    WHERE id = :id";
        } else {
            unset($data['password']);
            $sql = "UPDATE users SET
                        username = :username,
                        full_name = :full_name,
                        role = :role
                    WHERE id = :id";
        }
        return $this->db->prepare($sql)->execute($data);
    }

    public function countTransactions(int $id): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM transactions WHERE user_id = ?"
        );
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    // Menghapus user.
    public function remove(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

public function findByUsername(string $username)
{
    $stmt = $this->db->prepare(
        "SELECT id, username, password, full_name, role
         FROM users
         WHERE username = :username
         LIMIT 1"
    );
    $stmt->execute([
        'username' => $username
    ]);
    return $stmt->fetch();
}

}