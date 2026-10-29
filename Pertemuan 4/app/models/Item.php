<?php

class Item extends Model
{
    protected string $table = 'items';
    // ini untk memberi tahu database untuk ambil semua barang
    public function all(): array
    {
        return $this->db->query(
            "SELECT 
                items.*,
                categories.name AS category_name
             FROM items
             LEFT JOIN categories 
             ON categories.id = items.category_id
             ORDER BY items.id ASC"
        )->fetchAll();
    }
    // paginate atau limit data 
    public function paginate(
        int $limit,
        int $offset,
        string $search = '',
        string $category = ''
    ): array {
        $sql = "
            SELECT 
                items.*,
                categories.name AS category_name
            FROM items
            LEFT JOIN categories
            ON categories.id = items.category_id
        ";
        $where = [];
        $params = [];
        if ($search !== '') {
            $where[] = "(items.name LIKE :search OR items.code LIKE :search)";
            $params['search'] = "%$search%";
        }
        if ($category !== '') {
            $where[] = "items.category_id = :category";
            $params['category'] = $category;
        }
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= "
            ORDER BY items.id ASC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );
        $stmt->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function count(
        string $search = '',
        string $category = ''
    ): int {
        $sql = "
            SELECT COUNT(*)
            FROM items
        ";
        $where = [];
        $params = [];
        if ($search !== '') {
            $where[] = "(name LIKE :search OR code LIKE :search)";
            $params['search'] = "%$search%";
        }
        if ($category !== '') {
            $where[] = "category_id = :category";
            $params['category'] = $category;
        }
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key=>$value) {
            $stmt->bindValue(":$key",$value);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    // ini untuk memberi tahu database untuk cari satu barang
    public function find(int $id)
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM items
             WHERE id=?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ini fungsi memberi tahu database untuk tambah barang
    public function create(array $data): bool
    {
        return $this->db->prepare(
            "INSERT INTO items
            (
                code,
                name,
                category_id,
                quantity,
                unit,
                price,
                entry_date,
                description
            )
            VALUES
            (
                :code,
                :name,
                :category_id,
                :quantity,
                :unit,
                :price,
                :entry_date,
                :description
            )"
        )->execute($data);
    }

    // ini fungsi memberi tahu database untuk update barang
    public function update(int $id,array $data): bool
    {
        $data['id']=$id;
        return $this->db->prepare(
            "UPDATE items SET
            code=:code,
            name=:name,
            category_id=:category_id,
            quantity=:quantity,
            unit=:unit,
            price=:price,
            entry_date=:entry_date,
            description=:description
            WHERE id=:id"
        )->execute($data);
    }

    // upate stok
 public function updateStock(int $id, int $quantity, string $type)
{
    if ($type == 'IN') {
        $sql = "
            UPDATE items
            SET quantity = quantity + $quantity
            WHERE id = $id
        ";
    } else {
        $sql = "
            UPDATE items
            SET quantity = quantity - $quantity
            WHERE id = $id
        ";
    }
    return $this->db->query($sql);
}

    // ini fungsi memberi tahu database untuk hapus barang
    public function delete(int $id): bool
    {
        return $this->db->prepare(
            "DELETE FROM items WHERE id=?"
        )->execute([$id]);
    }

    // generate kode otomatis ITM0001
    public function generateCode(): string
    {
        $max = $this->db->query(
            "SELECT 
            MAX(CAST(SUBSTRING(code,4) AS UNSIGNED))
            FROM items
            WHERE code LIKE 'ITM%'"
        )->fetchColumn();

        return 'ITM' .
            str_pad(
                (string)(($max ? (int)$max : 0)+1),
                4,
                '0',    
                STR_PAD_LEFT
            );
    }
}