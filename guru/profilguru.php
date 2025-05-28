<?php
include "koneksi.php";

session_start();

if (!isset($_SESSION['inputUsm'])) {
    header("Location: login.php");
}

$username = $_SESSION['inputUsm'];
$query = mysqli_query($conn, "SELECT * FROM akun WHERE usm = '$username'");

if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    $id_guru = $data['id'];
    $query_guru = mysqli_query($conn, "SELECT * FROM guru WHERE id_guru = '$id_guru'");

    if (mysqli_num_rows($query_guru) > 0) {
        $data_guru = mysqli_fetch_assoc($query_guru);
    } else {
        $data_guru = null;
    }
} else {
    echo "Data pengguna tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="gurumenu.css">
    <title>Menu Guru</title>
</head>
<body>
    <div class="container col-lg-4 mt-5">
        <div class="card mb-3">
            <a class="profile" href="gurumenu.php">
                <img src="logo.png" alt="Logo" class="pflogo">
            </a>
            <h2>Detail Pengguna</h2>
            <?php if ($data_guru): ?>
            <p><strong>Nama:</strong> <?= $data_guru['nama_guru']?></p>
            <p><strong>E-mail:</strong> <?= $data_guru['email_guru']?></p>
            <p><strong>Mapel:</strong> <?= $data_guru['mapel_guru']?></p>
            <p><strong>Username:</strong> <?= $data_guru['usm_guru']?></p>
            <p><strong>NIP:</strong> <?= $data_guru['nip_guru']?></p>
            <?php endif; ?>
            <a href="proses.php?logout=true" class="btn btn-danger">Logout</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>