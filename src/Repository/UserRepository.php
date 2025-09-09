<?php
namespace App\Repository;

use PDO;

class UserRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function createTableIfNotExists(): void {
        $driver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            // SQLite syntax
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT NOT NULL UNIQUE,
                    password TEXT NOT NULL,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                );
            ");
        } else {
            // MySQL syntax
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        }
    }

    public function addUser(string $name, string $email, string $passwordHash): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password) 
            VALUES (:name, :email, :password)
        ");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function listUsers(): array {
        $stmt = $this->pdo->query("
            SELECT id, name, email, created_at 
            FROM users 
            ORDER BY id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users 
            WHERE email = :email 
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}