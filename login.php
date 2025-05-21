<?php
include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
        <title>Login</title>
    </head>
    <body>
        <div class="container col-lg-5">
            <div class="card mb-3">
                <div class="card-body">
                    <form method="post" action="proses.php">
                        <label for="inputUsm" class="form-label">Username</label>
                            <input type="password" id="inputUsm" class="form-control" placeholder="Masukkan username">
                        <label for="inputPw" class="form-label">Password</label>
                            <input type="password" id="inputPw" class="form-control" placeholder="Masukkan password">
                    </form>        
               </div>
            </div>
            <center>
            <button type="submit" class="btn btn-primary" name="login">Masuk</button>
            </center>
        </div>
    </body>
    <footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    </footer>
</html>