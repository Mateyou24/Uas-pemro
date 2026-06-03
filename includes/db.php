<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db_name = "boardgame_hub";

$conn = new mysqli($host, $user, $pass, $db_name, 3306);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}