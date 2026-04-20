<?php

require_once(__DIR__ . "/BaseModel.php");

class UserModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addUser(string $name, string $email, string $password, string $role): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, password_hash, role)
                  VALUES (:name, :email, :password_hash, :role)";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $hashedPassword);
        $stmt->bindParam(':role', $role);

        if (!$stmt->execute()) {
            error_log("Error executing query: " . implode(", ", $stmt->errorInfo()));
            return false;
        }

        return true;
    }

    public function getAllUsers(): array
    {
        $stmt = $this->pdo->query("SELECT id, name, email, role FROM users ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function getUserById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT id, name, email, role FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getUserByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function deleteUser(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            error_log("Error executing query: " . implode(", ", $stmt->errorInfo()));
            return false;
        }

        return true;
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        if ($excludeId !== null) {
            $query .= " AND id != :id";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        if ($excludeId !== null) {
            $stmt->bindParam(':id', $excludeId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn() > 0;
    }
}
