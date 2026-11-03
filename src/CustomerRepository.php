<?php
declare(strict_types=1);

namespace App;

use PDO;

final readonly class CustomerRepository
{
    public function __construct(private PDO $db) {}

    /** @return array{items: list<array>, total: int} */
    public function paginate(string $search, int $page, int $perPage): array
    {
        $where  = '';
        $params = [];
        if ($search !== '') {
            $where = 'WHERE name LIKE :q OR email LIKE :q2 OR phone LIKE :q3';
            $like = '%' . $search . '%';
            $params = [':q' => $like, ':q2' => $like, ':q3' => $like];
        }

        $count = $this->db->prepare("SELECT COUNT(*) FROM customers $where");
        $count->execute($params);
        $total = (int) $count->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM customers $where ORDER BY id DESC LIMIT :limit OFFSET :offset");
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->execute();

        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM customers WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function emailExists(string $email, ?int $exceptId = null): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM customers WHERE email = ? AND id <> ?');
        $stmt->execute([$email, $exceptId ?? 0]);
        return (bool) $stmt->fetchColumn();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)');
        $stmt->execute([$data['name'], $data['email'], $data['phone'] ?: null, $data['address'] ?: null]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?');
        $stmt->execute([$data['name'], $data['email'], $data['phone'] ?: null, $data['address'] ?: null, $id]);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM customers WHERE id = ?')->execute([$id]);
    }
}
