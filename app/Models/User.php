<?php
    namespace App\Models;

    class User extends Model {
        protected string $table = 'users';
        protected string $primaryKey = 'id';
        protected array $fillable = ['name', 'email', 'password', 'created_at'];

        public function findByEmail(string $email): array | false {
            return $this->db->fetchOne(
                "SELECT * FROM {$this->table} WHERE email = ?",
                [$email]
            );
        }

        public function verifyPassword(string $plainPassword, string $hash): bool {
            return password_verify($plainPassword, $hash);
        }
    }
?>