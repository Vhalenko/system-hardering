<?php

require_once(__DIR__ . "/../models/ComicModel.php");
require_once(__DIR__ . "/BaseController.php");

class ComicController extends BaseController
{
    private $comicModel;
    public function __construct()
    {
        $this->comicModel = new ComicModel();
    }

    public function getAllComics(): array
    {
        $this->requireAuth();
        $comics = $this->comicModel->getAllComics();
        return ['success' => true, 'comics' => $comics];
    }

    public function getComicById(int $id): array {
        $comic = $this->comicModel->getComicById($id);
        return ['success' => true, 'comic' => $comic];
    }

    public function addComic(): array
    {
        $this->requireRole('admin', 'super_admin');
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $serie  = trim($body['serie']  ?? '');
        $number = (int) ($body['number'] ?? 0);
        $title  = trim($body['title']  ?? '');

        if (empty($serie) || $number <= 0 || empty($title)) {
            http_response_code(400);
            return ['success' => false, 'message' => 'Serie, number and title are required'];
        }

        if ($this->comicModel->comicExists($serie, $number)) {
            http_response_code(400);
            return ['success' => false, 'message' => "Issue #{$number} of '{$serie}' already exists"];
        }

        $ok = $this->comicModel->addComic($serie, $number, $title);

        if (!$ok) {
            http_response_code(500);
            return ['success' => false, 'message' => 'Failed to create comic'];
        }

        http_response_code(201);
        return ['success' => true, 'message' => 'Comic created'];
    }

    public function updateComic(int $id): array
    {
        $this->requireRole('admin', 'super_admin');
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $serie  = trim($body['serie']  ?? '');
        $number = (int) ($body['number'] ?? 0);
        $title  = trim($body['title']  ?? '');

        if (empty($serie) || $number <= 0 || empty($title)) {
            http_response_code(400);
            return ['success' => false, 'message' => 'Serie, number and title are required'];
        }

        if (!$this->comicModel->getComicById($id)) {
            http_response_code(404);
            return ['success' => false, 'message' => 'Comic not found'];
        }

        if ($this->comicModel->comicExists($serie, $number, $id)) {
            http_response_code(400);
            return ['success' => false, 'message' => "Issue #{$number} of '{$serie}' already exists"];
        }

        $ok = $this->comicModel->updateComic($id, $serie, $number, $title);

        if (!$ok) {
            http_response_code(500);
            return ['success' => false, 'message' => 'Failed to update comic'];
        }

        return ['success' => true, 'message' => 'Comic updated'];
    }

    public function deleteComic(int $id): array
    {
        $this->requireRole('admin', 'super_admin');
        if (!$this->comicModel->getComicById($id)) {
            http_response_code(404);
            return ['success' => false, 'message' => 'Comic not found'];
        }

        $ok = $this->comicModel->deleteComic($id);

        if (!$ok) {
            http_response_code(500);
            return ['success' => false, 'message' => 'Failed to delete comic'];
        }

        return ['success' => true, 'message' => 'Comic deleted'];
    }
}
