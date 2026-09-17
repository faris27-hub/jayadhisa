<?php
require_once 'functions.php';
$title = 'JayaDhisa Market';
$q = trim($_GET['q'] ?? ''); $cat = (int)($_GET['kategori'] ?? 0);
$cats = $koneksi->query('SELECT * FROM kategori ORDER BY nama_kategori');
$sql = 'SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON k.id_kategori=p.id_kategori WHERE 1'; $types=''; $params=[];
if ($q !== '') { $sql .= ' AND p.nama_produk LIKE ?'; $types.='s'; $params[]="%$q%"; }
if ($cat) { $sql .= ' AND p.id_kategori=?'; $types.='i'; $params[]=$cat; }
$sql .= ' ORDER BY p.id_produk DESC'; $st=$koneksi->prepare($sql); if($types) $st->bind_param($types,...$params); $st->execute(); $products=$st->get_result();
include 'header.php';
?>
<section class="hero container"><div><span class="pill">DEMO E-COMMERCE</span><h1>Belanja mudah, dari produk sampai pembayaran.</h1><p>Alur lengkap untuk demo: register, login, katalog, keranjang, checkout, payment sandbox, riwayat pesanan, admin, CRUD, dan laporan.</p><div class="actions"><a class="btn" href="produk.php">Mulai Belanja</a><a class="btn outline light" href="<?= user() ? 'pesanan.php' : 'login.php' ?>">Lihat Pesanan</a></div></div><div class="hero-card">🛍️<strong>JayaDhisa</strong><small>Market Demo</small></div></section>
<div class="container"><div class="section-head"><div><h2>Produk Pilihan</h2><p class="muted">Temukan produk yang tersedia.</p></div><form class="search" method="get"><input name="q" value="<?=e($q)?>" placeholder="Cari produk..."><select name="kategori"><option value="0">Semua kategori</option><?php while($c=$cats->fetch_assoc()): ?><option value="<?=$c['id_kategori']?>" <?=$cat==$c['id_kategori']?'selected':''?>><?=e($c['nama_kategori'])?></option><?php endwhile;?></select><button class="btn">Cari</button></form></div><div class="grid"><?php while($p=$products->fetch_assoc()): ?><article class="card"><div class="thumb"><?= $p['image_url'] ? '<img src="'.e($p['image_url']).'" alt="'.e($p['nama_produk']).'">' : '📦' ?></div><div class="card-body"><small><?=e($p['nama_kategori']??'Tanpa kategori')?></small><h3><?=e($p['nama_produk'])?></h3><strong><?=rupiah($p['harga'])?></strong><p class="muted">Stok: <?=$p['stok']?></p><div class="actions"><a class="btn outline" href="detail_produk.php?id=<?=$p['id_produk']?>">Detail</a><?php if($p['stok']>0): ?><a class="btn" href="detail_produk.php?id=<?=$p['id_produk']?>">Beli</a><?php else: ?><span class="btn disabled">Habis</span><?php endif;?></div></div></article><?php endwhile; ?></div></div>
<?php include 'footer.php'; ?>
