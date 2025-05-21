<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "scheduling";

    $conn = mysqli_connect($hostname, $username, $password, $database);

    if($conn-> connect_error) {
        die("koneksi gagal:" . $conn->connect_error);
    }
?>