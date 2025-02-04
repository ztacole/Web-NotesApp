<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow" style="width: 350px;">
        <h4 class="text-center mb-4">Register</h4>
        <form method="post" onsubmit="return validatePassword()">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Masukkan password" required>
            </div>
            <div class="mb-3">
                <label for="confirm-password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm-password"
                    placeholder="Konfirmasi password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Util/CoreFunction.js"></script>
</body>

</html>

<?php

include 'data/controller/UserController.php';
include 'util/CoreFunction.php';

// Memeriksa metode permintaan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Memvalidasi email
    if (!CoreFunction::validatePassword($password)) {
        die("Password must contain at least 8 characters, including at least one uppercase letter, one lowercase letter, one number, and one special character.");
    }

    // Memvalidasi konfirmasi password
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Memhash password
    $hashedPassword = CoreFunction::hashPassword($password);

    // Memanggil fungsi register
    $userController = new UserController();
    if ($userController->register($email, $hashedPassword)) {
        ?>
        <script>alert("Pendaftaran berhasil. Silahkan login.");</script>
        <?php
        header("Location: login.php");
        exit;
    } else {
        ?>
        <script>alert("Pendaftaran gagal. Email sudah terdaftar.");</script>
        <?php
    }
}