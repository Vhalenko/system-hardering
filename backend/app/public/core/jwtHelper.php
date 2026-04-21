<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    public static function generate(array $user): string
    {
        $payload = [
            'iat'  => time(),
            'exp'  => time() + (int) $_ENV['JWT_EXPIRY'],
            'user' => [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ]
        ];

        return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
    }

    public static function validate(string $token): array|false
    {
        try {
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
            return (array) $decoded->user;
        } catch (Exception $e) {
            return false;
        }
    }
}