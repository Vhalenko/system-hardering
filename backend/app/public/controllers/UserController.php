<?php

require_once(__DIR__ . "/../models/UserModel.php");
require_once(__DIR__ . "/BaseController.php");

class UserController extends BaseController
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login(): array
    {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $email    = trim($body['email']    ?? '');
        $password = trim($body['password'] ?? '');

        if (empty($email) || empty($password)) {
            http_response_code(400);
            return ['success' => false, 'message' => 'Email and password are required'];
        }

        $user = $this->userModel->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            http_response_code(401);
            return ['success' => false, 'message' => 'Invalid email or password'];
        }

        $userData = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];

        $token = JwtHelper::generate($userData);

        http_response_code(200);
        return ['success' => true, 'token' => $token, 'user' => $userData];
    }

    public function logout(): array
    {
        $_SESSION = [];
        session_destroy();
        return ['success' => true, 'message' => 'Logged out'];
    }

    public function getAllUsers(): array
    {
        $this->requireRole('admin', 'super_admin');
        $users = $this->userModel->getAllUsers();
        return ['success' => true, 'users' => $users];
    }

    public function createUser(): array
    {
        $this->requireRole('admin', 'super_admin');
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $name     = trim($body['name']     ?? '');
        $email    = trim($body['email']    ?? '');
        $password = trim($body['password'] ?? '');
        $role     = trim($body['role']     ?? '');

        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            http_response_code(400);
            return ['success' => false, 'message' => 'All fields are required'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            return ['success' => false, 'message' => 'Invalid email address'];
        }

        if ($this->userModel->emailExists($email)) {
            http_response_code(400);
            return ['success' => false, 'message' => 'Email is already in use'];
        }

        $ok = $this->userModel->addUser($name, $email, $password, $role);

        if (!$ok) {
            http_response_code(500);
            return ['success' => false, 'message' => 'Failed to create user'];
        }

        http_response_code(201);
        return ['success' => true, 'message' => 'User created'];
    }
}
