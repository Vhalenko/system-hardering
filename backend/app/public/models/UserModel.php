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

        $query = "INSERT INTO user (name, email, password_hash, role)
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
}
