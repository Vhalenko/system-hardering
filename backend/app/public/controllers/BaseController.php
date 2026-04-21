<?php

require_once __DIR__ . '/../core/jwtHelper.php';

abstract class BaseController
{
    protected function requireAuth(): array
    {
        $token = $this->getBearerToken();

        if (!$token) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No token provided']);
            exit;
        }

        $user = JwtHelper::validate($token);

        if (!$user) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
            exit;
        }

        return $user;
    }

    protected function requireRole(string ...$roles): array
    {
        $user = $this->requireAuth();

        if (!in_array($user['role'], $roles, true)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Forbidden']);
            exit;
        }

        return $user;
    }

    private function getBearerToken(): ?string
    {
        $headers = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';

        if (str_starts_with($headers, 'Bearer ')) {
            return substr($headers, 7);
        }

        return null;
    }

    protected function body(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}