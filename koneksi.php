<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'jayadhisa';

$koneksi = mysqli_connect($host, $user, $password, $database);
if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, 'utf8mb4');

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function rupiah($value) {
    return 'Rp ' . number_format((float)$value, 0, ',', '.');
}
function redirect($url) {
    header('Location: ' . $url);
    exit;
}
