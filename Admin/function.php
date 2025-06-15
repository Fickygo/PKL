<?php

session_start();

//Koneksi
$c = mysqli_connect('localhost', 'root', '', 'pdp_printing');

//Login
if (isset($_POST['login'])) {
    //Initiate variabel
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = mysqli_query($c, "SELECT * FROM admin WHERE username='$username' and password='$password' ");
    $hitung = mysqli_num_rows($check);

    if ($hitung > 0) {
        //Jika ketemu
        //berhasil login

        $_SESSION['login'] = 'True';
        header('location:index.php');
    } else {
        //gak ketemu
        //gagal login
        echo '
        <script>alert("Username atau Password salah!");
        window.location.href="login.php"
        </script>
        ';
    }
}
