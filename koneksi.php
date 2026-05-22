<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "resepkita";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Tidak Terkoneksi");
}
?>