<?php

include __DIR__ . '/../model/User.php';
include __DIR__ . '/../remote/Connection.php';

class UserController
{
    private $connection = null;
    public function __construct() {
        $this->connection = Connection::connect();
    }

    public function login(string $email, string $password): User|string
    {
        $statement = $this->connection->prepare("SELECT * FROM user WHERE email = :email");
        $statement->execute(['email' => $email]);

        if (!$user = $statement->fetchObject(User::class)) {
            // Email tidak ditemukan
            return "User not found";
        }

        if (!password_verify($password, $user->password)) {
            // Password salah
            return "Invalid password";
        }

        return $user;
    }

    public function register(string $email, string $password): bool
    {
        $checkStmt = $this->connection->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
        $checkStmt->execute(['email' => $email]);
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            // Email sudah terdaftar, hentikan proses registrasi
            return false;
        }

        $statement = $this->connection->prepare("INSERT INTO user (email, password) VALUES (:email, :password)");
        return $statement->execute(['email' => $email, 'password' => $password]);
    }
}