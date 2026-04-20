<?php

require_once(__DIR__ . "/BaseModel.php");

class ComicModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllComics(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM comics ORDER BY serie ASC, number ASC");
        return $stmt->fetchAll();
    }

    public function getComicById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comics WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function addComic(string $serie, int $number, string $title): bool
    {
        $query = "INSERT INTO comics (serie, number, title)
                  VALUES (:serie, :number, :title)";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':serie',  $serie);
        $stmt->bindParam(':number', $number, PDO::PARAM_INT);
        $stmt->bindParam(':title',  $title);

        if (!$stmt->execute()) {
            error_log("Error executing query: " . implode(", ", $stmt->errorInfo()));
            return false;
        }

        return true;
    }

    public function updateComic(int $id, string $serie, int $number, string $title): bool
    {
        $query = "UPDATE comics
                  SET serie = :serie, number = :number, title = :title
                  WHERE id = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id',     $id,     PDO::PARAM_INT);
        $stmt->bindParam(':serie',  $serie);
        $stmt->bindParam(':number', $number, PDO::PARAM_INT);
        $stmt->bindParam(':title',  $title);

        if (!$stmt->execute()) {
            error_log("Error executing query: " . implode(", ", $stmt->errorInfo()));
            return false;
        }

        return true;
    }

    public function deleteComic(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM comics WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            error_log("Error executing query: " . implode(", ", $stmt->errorInfo()));
            return false;
        }

        return true;
    }

    public function comicExists(string $serie, int $number, ?int $excludeId = null): bool
    {
        $query = "SELECT COUNT(*) FROM comics WHERE serie = :serie AND number = :number";
        if ($excludeId !== null) {
            $query .= " AND id != :id";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':serie',  $serie);
        $stmt->bindParam(':number', $number, PDO::PARAM_INT);
        if ($excludeId !== null) {
            $stmt->bindParam(':id', $excludeId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn() > 0;
    }
}
