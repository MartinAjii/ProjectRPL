<?php
include "koneksi.php";

session_start();

if (!isset($_SESSION['inputUsm'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu Guru</title>
    </head>
    <body>
        <a href="proses.php?logout=true" class="btn btn-danger">
            <button type="submit" class="btn btn-danger">Logout</button>
        </a>
    </body>
</html>