<?php
session_start();
include("koneksi.php");

//Login
if (isset($_POST['login'])) {
    $username = $_POST['inputUsm'];
    $password = $_POST['inputPw'];

    $query = mysqli_query($conn, "SELECT * FROM akun WHERE usm = '$username' AND pass = '$password'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['inputUsm'] = $username;
        $_SESSION['id'] = $data['id'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == "operator") {
            header("Location: operatormenu.php");
            exit();
        } else if ($data['role'] == "siswa") {
            header("Location: siswamenu.php");
            exit();
        } else if ($data['role'] == "guru") {
            header("Location: gurumenu.php");
            exit();
        } else {
            $_SESSION['error'] = "Peran pengguna tidak dikenali.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Username atau password salah.";
        header("Location: login.php");
        exit();
    }
}

if (isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php?logout=true");
}
?>