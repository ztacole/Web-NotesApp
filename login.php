<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow" style="width: 350px;">
        <h4 class="text-center mb-4">Login</h4>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p align="center">Belum punya akun? <a href="register.php">Daftar</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

    // Memanggil fungsi login
    $userController = new UserController();
    $result = $userController->login($email, $password);

    // Memeriksa hasil login
    if (is_string($result)) {
        ?>
        <script>alert("<?php echo $result; ?>");</script>
        <?php
        exit;
    } else {
        session_start();
        $_SESSION['idUser'] = $result->id;
        $_SESSION['email'] = $result->email;
        header("Location: index.php");
    }
}