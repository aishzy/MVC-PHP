<?php
    // base model 
    namespace App\Models;

    use Core\Database;

    abstract class Model {

        protected string $table;
        protected string $primaryKey = 'id';
        protected array $fillable = [];
        protected Database $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        // CRUD 
        public function all(string $orderBy = 'id', string $direction = 'ASC'): array {
            return $this->db->fetchAll(
                "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}"
            );
        }

        public function find(int | string $id): array | false {
            return $this->db->fetchOne(
                "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
                [$id]
            );
        }

        public function findOrFail(int | string $id): array {
            $record = $this->find($id);
            if (!$record) {
                throw new \RuntimeException("Record not found in {$this->table}.", 404);
            }
            return $record;
        }

        public function create(array $data): string {
            $filtered = $this->filterFillable($data);
            return $this->db->insert($this->table, $filtered);
        }

        public function update(int | string $id, array $data): int {
            $filtered = $this->filterFillable($data);
            return $this->db->update(
                $this->table,
                $filtered,
                "{$this->primaryKey} = ?",
                [$id]
            );
        }

        public function delete(int|string $id): int {
            return $this->db->delete(
                $this->table, 
                "{$this->primaryKey} = ?",
                [$id]
            );
        }

        public function where(string $column, mixed $value): array {
            return $this->db->fetchAll(
                "SELECT * FROM {$this->table} WHERE {$column} = ?",
                [$value]
            );
        }

        public function count(): int {
            $row = $this->db->fetchOne("SELECT COUNT(*) AS total FROM {$this->table}");
            return (int)($row['total'] ?? 0);
        }

        public function paginate(int $page = 1, int $perPage = 10): array {
            $offset = ($page - 1) * $perPage;
            $items = $this->db->fetchAll(
                "SELECT * FROM {$this->table} LIMIT ? OFFSET ?",
                [$perPage, $offset]
            );
            $total = $this->count();

            return [
                'data' => $items,
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => (int)ceil($total / $perPage),
            ];
        }

        // Utility 
        private function filterFillable(array $data): array {
            if (empty($this->fillable)) return $data;
            return array_intersect_key($data, array_flip($this->fillable));
        }
    }
?>