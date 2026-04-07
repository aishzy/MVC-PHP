<?php
    namespace Core;

    use PDO;

    class Database {
        private static ?Database $instance = null;
        private PDO $pdo;

        private function __construct() {
            $config = require BASE_PATH . '/config/database.php';

            $dsn = sprintf(
                '%s:host=%s;port=%s;dname=%s;charset=%s',
                $config['driver'],
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );
            
                    $this->pdo = new PDO(
                        $dsn,
                        $config['username'],
                        $config['password'],
                        $config['options']
                    );
        }

        public static function getInstance(): self {
            if (self::$instance === null) {
                self::$instance = new Self();
            }
            return self::$instance;
        }

        // Query Helpers 
        public function query(string $sql, array $params = []): \PDOStatement {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        }

        public function fetchAll(string $sql, array $params = []): array {
            return $this->query($sql, $params)->fetchAll();
        }

        public function fetchOne(string $sql, array $params = []): array | false {
            return $this->query($sql, $params)->fetch();
        }

        public function insert(string $table, array $data): string {
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $this->query(
                "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})",
                array_values($data)
            );
            return $this->pdo->lastInsertId();
        }

        public function update(string $table, array $data, string $where, array $whereParams = []): int {
            $set = implode(', ', array_map(fn($col) => '{$col} = ?', array_keys($data)));
            $params = array_merge(array_values($data, $whereParams));
            return $this->query("UPDATE {$table} SET {$set} WHERE {$where}", $params)->rowCount();
        }

        public function delete(string $table, string $where, array $params = []): int {
            return $this->query("DELETE FROM {$table} WHERE {$where}", $params)->rowCount();
        }

        public function beginTransaction(): void {
            $this->pdo->beginTransaction();
        }

        public function commit(): void {
            $this->pdo->commit();
        }

        public function rollback(): void {
            $this->pdo->rollback();
        }

        // Prevent cloning and unserialization of the singleton
        private function __clone() {

        }

        private function __wakeup(): never {
            throw new \RuntimeException("Cannot unserialize singleton.");
        }
    }
?>  