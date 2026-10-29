<?php

class Transaction extends Model
{
protected string $table = 'transactions';
public function all(): array
{
    return $this->db->query(
    "
    SELECT 
    transactions.*,
    items.name AS item_name,
    users.username
    FROM transactions
    LEFT JOIN items
    ON items.id = transactions.item_id
    LEFT JOIN users
    ON users.id = transactions.user_id
    ORDER BY transactions.id ASC
    "
    )->fetchAll();
}

//  create
public function create(array $data): bool
{
    return $this->db->prepare(
        "
        INSERT INTO transactions
        (
            item_id,
            user_id,
            type,
            quantity,
            notes
        )
        VALUES
        (
            :item_id,
            :user_id,
            :type,
            :quantity,
            :notes
        )
        "
    )->execute($data);
}

// fungsi untuk hapus data
public function delete(int $id): bool
{
    return $this->db->prepare(
        "DELETE FROM transactions WHERE id=?"
    )->execute([$id]);
}

public function find(int $id)
{
    $stmt = $this->db->prepare(
        "SELECT *
         FROM transactions
         WHERE id=?"
    );
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// update data
public function update(int $id,array $data): bool
{
    $data['id']=$id;
    return $this->db->prepare(
        "UPDATE transactions SET
        item_id=:item_id,
        type=:type,
        quantity=:quantity,
        notes=:notes
        WHERE id=:id"
    )->execute($data);
}

// paginate
public function paginate(
    string $search = '',
    string $type = '',
    int $limit = 5,
    int $page = 1
): array {
    // Membatasi jumlah data
    $allowedLimits = [5, 10, 25];

    if (!in_array($limit, $allowedLimits, true)) {
        $limit = 5;
    }
    $page = max(1, $page);
    $offset = ($page - 1) * $limit;
    // pencarian
    $where = [];
    $params = [];
    if ($search !== '') {
        $where[] = "(
            items.code LIKE :search_code
            OR items.name LIKE :search_name
            OR users.username LIKE :search_user
            OR transactions.notes LIKE :search_notes
        )";
        $keyword = '%' . $search . '%';
        $params['search_code'] = $keyword;
        $params['search_name'] = $keyword;
        $params['search_user'] = $keyword;
        $params['search_notes'] = $keyword;
    }
// filter jenis transaksi
    if (in_array($type, ['IN', 'OUT'], true)) {
        $where[] = "transactions.type = :type";
        $params['type'] = $type;
    }
    $whereSql = '';
    if (!empty($where)) {
        $whereSql = ' WHERE ' . implode(' AND ', $where);
    }
    // ini hitung total data sesuai pencarian dan filter
    $countSql = "
        SELECT COUNT(*)
        FROM transactions
        LEFT JOIN items
            ON items.id = transactions.item_id
        LEFT JOIN users
            ON users.id = transactions.user_id
        $whereSql
    ";
    $countStmt = $this->db->prepare($countSql);
    $countStmt->execute($params);
    $totalData = (int) $countStmt->fetchColumn();
    $dataSql = "
        SELECT
            transactions.*,
            items.code AS item_code,
            items.name AS item_name,
            users.username
        FROM transactions
        LEFT JOIN items
            ON items.id = transactions.item_id
        LEFT JOIN users
            ON users.id = transactions.user_id
        $whereSql
        ORDER BY transactions.id ASC
        LIMIT $limit OFFSET $offset
    ";
    $stmt = $this->db->prepare($dataSql);
    $stmt->execute($params);
    return [
        'data' => $stmt->fetchAll(),
        'total_data' => $totalData,
        'total_pages' => (int) ceil($totalData / $limit),
        'page' => $page,
        'limit' => $limit
    ];
}

}