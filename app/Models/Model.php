<?php
    // base model 
    namespace App\Models;

    use Core\Database;

    abstract class Model {

        protected string $table;
        protected string $primaryKey = 'id';
        protected array $fillable = [];
        protected Database $db;
    }
?>