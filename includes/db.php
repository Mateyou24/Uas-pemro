<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "boardgame_hub";

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}