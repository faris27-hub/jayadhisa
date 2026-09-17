<?php
require_once __DIR__ . '/koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

function user() { return $_SESSION['user'] ?? null; }
function require_login() { if (!user()) redirect('login.php'); }
function require_admin() { require_login(); if ((user()['role'] ?? 'user') !== 'admin') redirect('../index.php'); }
function cart_count() {
    $count = 0;
    foreach (($_SESSION['keranjang'] ?? []) as $qty) $count += max(0, (int)$qty);
    return $count;
}
function flash($type, $message) { $_SESSION['flash'] = [$type, $message]; }
function get_flash() { $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
function csrf() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function check_csrf() {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) die('CSRF token tidak valid.');
}
function cart_items($db) {
    $items = [];
    $total = 0;
    $cart = $_SESSION['keranjang'] ?? [];
    if (!$cart) return [$items, $total];
    $stmt = $db->prepare('SELECT id_produk, nama_produk, harga, stok FROM produk WHERE id_produk = ?');
    foreach ($cart as $id => $qty) {
        $id = (int)$id; $qty = max(0, (int)$qty);
        if ($qty < 1) continue;
        $stmt->bind_param('i', $id); $stmt->execute();
        $p = $stmt->get_result()->fetch_assoc();
        if (!$p || (int)$p['stok'] < 1) continue;
        $qty = min($qty, (int)$p['stok']);
        $subtotal = $qty * (float)$p['harga'];
        $items[] = ['p' => $p, 'qty' => $qty, 'subtotal' => $subtotal];
        $total += $subtotal;
    }
    return [$items, $total];
}
function order_status_label($status) {
    return [
        'pending' => 'Menunggu Pembayaran', 'paid' => 'Sudah Dibayar',
        'processing' => 'Diproses', 'shipped' => 'Dikirim',
        'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'
    ][$status] ?? ucfirst($status);
}
function payment_status_label($status) {
    return ['pending' => 'Menunggu', 'paid' => 'Berhasil', 'failed' => 'Gagal'][$status] ?? ucfirst($status);
}
