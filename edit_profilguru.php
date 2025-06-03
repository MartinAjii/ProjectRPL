<?php
include "koneksi.php";

session_start();

if (!isset($_SESSION['inputUsm'])) {
    header("Location: login.php");
}

$username = $_SESSION['inputUsm'];

// Ambil data akun dan guru
$query = mysqli_query($conn, "SELECT * FROM akun WHERE usm = '$username'");
$data_akun = mysqli_fetch_assoc($query);
$id_akun = $data_akun['id'];

$query_guru = mysqli_query($conn, "SELECT * FROM guru WHERE id_akun = '$id_akun'");
$data_guru = mysqli_fetch_assoc($query_guru);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Edit Profil Guru</title>
        <link rel="stylesheet" href="gurumenu.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    </head>
    <body>
    <div class="container col-lg-6 mt-5">
        <h2>Edit Profil Guru</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
            unset($_SESSION['success']);
        }
        ?>

        <form method="POST" action="proses.php" enctype="multipart/form-data">
            <h4>Data Profil</h4>
            <input type="hidden" name="action" value="update_profile">
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data_guru['nama_guru']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data_guru['email_guru']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Mapel</label>
                <input type="text" name="mapel" class="form-control" value="<?= htmlspecialchars($data_guru['mapel_guru']) ?>" required>
            </div>
            <div class="mb-3">
                <label>NIP</label>
                <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($data_guru['nip_guru']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data_guru['usm_guru']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>

        <hr>

        <form method="POST" action="proses.php">
            <h4>Ubah Password</h4>
            <input type="hidden" name="action" value="change_password">
            <div class="mb-3">
                <label>Password Lama</label>
                <input type="password" name="old_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password Baru</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning">Ubah Password</button>
        </form>

        <hr>

        <form method="POST" action="proses.php" enctype="multipart/form-data">
            <h4>Upload Foto Profil</h4>
            <?php if (!empty($data_guru['foto_guru']) && file_exists($data_guru['foto_guru'])): ?>
                <img src="<?= $data_guru['foto_guru'] ?>" alt="Foto Profil" style="width:150px; height:150px; object-fit:cover; border-radius:50%; margin-bottom:10px;">
            <?php else: ?>
                <p>Tidak ada foto profil.</p>
            <?php endif; ?>
            <input type="hidden" name="action" value="upload_photo">
            <input type="file" name="foto" accept="image/*" class="form-control" required>
            <button type="submit" class="btn btn-success mt-2">Upload Foto</button>
        </form>

        <a href="profilguru.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>
</html>