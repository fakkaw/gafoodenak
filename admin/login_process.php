<?php
session_start();
require_once '../api/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header("Location: index.php?error=Username dan Password tidak boleh kosong");
        exit();
    }

    // Ambil data admin dari database
    $sql = "SELECT id, username, password FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $admin['password'])) {
            // Password benar, buat session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            // Arahkan ke dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Password salah
            header("Location: index.php?error=Username atau Password salah");
            exit();
        }
    } else {
        // Username tidak ditemukan
        header("Location: index.php?error=Username atau Password salah");
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    // Jika bukan metode POST, kembalikan ke halaman login
    header("Location: index.php");
    exit();
}
?>