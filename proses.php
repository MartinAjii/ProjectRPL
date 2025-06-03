<?php
session_start();
include("koneksi.php");

if (isset($_POST['login'])) {
    // Proses login
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
            // Cek apakah data guru sudah ada, jika belum insert
            $id_akun = $data['id'];
            $usm = $data['usm'];
            $pass = $data['pass'];

            $cekGuru = mysqli_query($conn, "SELECT * FROM guru WHERE usm_guru = '$usm'");
            if (mysqli_num_rows($cekGuru) == 0) {
                mysqli_query($conn, "INSERT INTO guru (usm_guru, pw_guru, id_akun) VALUES ('$usm', '$pass', '$id_akun')");
            }
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
} else if (isset($_POST['action'])) {
    // Proses update profile, ganti password, upload foto dll
    if (!isset($_SESSION['inputUsm'])) {
        $_SESSION['error'] = "Anda harus login terlebih dahulu.";
        header("Location: login.php");
        exit();
    }

    $username = $_SESSION['inputUsm'];

    // Ambil data akun dan guru
    $query = mysqli_query($conn, "SELECT * FROM akun WHERE usm = '$username'");
    $data_akun = mysqli_fetch_assoc($query);
    $id_akun = $data_akun['id'];

    $query_guru = mysqli_query($conn, "SELECT * FROM guru WHERE id_akun = '$id_akun'");
    $data_guru = mysqli_fetch_assoc($query_guru);

    $action = $_POST['action'];

    switch ($action) {
        case 'update_profile':
            $nama = mysqli_real_escape_string($conn, $_POST['nama']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $mapel = mysqli_real_escape_string($conn, $_POST['mapel']);
            $nip = mysqli_real_escape_string($conn, $_POST['nip']);
            $usm_guru = mysqli_real_escape_string($conn, $_POST['username']);

            // Cek apakah username baru sudah dipakai user lain
            $check_user = mysqli_query($conn, "SELECT * FROM akun WHERE usm = '$usm_guru' AND id != '$id_akun'");
            if (mysqli_num_rows($check_user) > 0) {
                $_SESSION['error'] = "Username sudah dipakai pengguna lain.";
                header("Location: edit_profilguru.php");
                exit();
            }

            // Update data guru dan akun
            $update_guru = mysqli_query($conn, "UPDATE guru SET nama_guru='$nama', email_guru='$email', mapel_guru='$mapel', nip_guru='$nip', usm_guru='$usm_guru' WHERE id_guru = ".$data_guru['id_guru']);
            $update_akun = mysqli_query($conn, "UPDATE akun SET usm='$usm_guru' WHERE id='$id_akun'");

            if ($update_guru && $update_akun) {
                $_SESSION['inputUsm'] = $usm_guru; // update session username jika berubah
                $_SESSION['success'] = "Profil berhasil diperbarui.";
            } else {
                $_SESSION['error'] = "Gagal memperbarui profil.";
            }
            header("Location: edit_profilguru.php");
            exit();
            break;

        case 'change_password':
            $old_password = $_POST['old_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($old_password !== $data_akun['pass']) {
                $_SESSION['error'] = "Password lama salah.";
            } else if ($new_password !== $confirm_password) {
                $_SESSION['error'] = "Password baru dan konfirmasi tidak cocok.";
            } else {
                $update_pass_akun = mysqli_query($conn, "UPDATE akun SET pass='$new_password' WHERE id='$id_akun'");
                $update_pass_guru = mysqli_query($conn, "UPDATE guru SET pw_guru='$new_password' WHERE id_akun='$id_akun'");

                if ($update_pass_akun && $update_pass_guru) {
                    $_SESSION['success'] = "Password berhasil diubah.";
                } else {
                    $_SESSION['error'] = "Gagal mengubah password.";
                }
            }
            header("Location: edit_profilguru.php");
            exit();
            break;

        case 'upload_photo':
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $file_tmp = $_FILES['foto']['tmp_name'];
                $file_name = $_FILES['foto']['name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($file_ext, $allowed)) {
                    $new_file_name = "profile_" . $data_guru['id_guru'] . "." . $file_ext;
                    $upload_dir = "uploads/profile_guru/";

                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    $upload_path = $upload_dir . $new_file_name;

                    if (move_uploaded_file($file_tmp, $upload_path)) {
                        mysqli_query($conn, "UPDATE guru SET foto_guru='$upload_path' WHERE id_guru=".$data_guru['id_guru']);
                        $_SESSION['success'] = "Foto profil berhasil diunggah.";
                    } else {
                        $_SESSION['error'] = "Gagal mengunggah foto.";
                    }
                } else {
                    $_SESSION['error'] = "Format foto tidak didukung. Gunakan jpg, jpeg, png, atau gif.";
                }
            } else {
                $_SESSION['error'] = "Tidak ada file foto yang diunggah.";
            }
            header("Location: edit_profilguru.php");
            exit();
            break;

        default:
            $_SESSION['error'] = "Aksi tidak dikenali.";
            header("Location: edit_profilguru.php");
            exit();
    }
} else {
    // Kalau tidak ada $_POST['login'] atau $_POST['action'], redirect ke login
    header("Location: login.php");
    exit();
}

// Logout handler bisa tetap di bagian bawah (optional)
if (isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php?logout=true");
    exit();
}
?>