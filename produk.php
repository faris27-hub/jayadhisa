<?php
require_once 'functions.php';

$title = 'Produk';
$q = trim($_GET['q'] ?? '');

/*
|--------------------------------------------------------------------------
| Ambil data produk dari database
|--------------------------------------------------------------------------
*/
$stmt = $koneksi->prepare("
    SELECT
        p.*,
        k.nama_kategori
    FROM produk p
    LEFT JOIN kategori k
        ON k.id_kategori = p.id_kategori
    WHERE p.nama_produk LIKE ?
    ORDER BY p.id_produk DESC
");

$like = "%$q%";
$stmt->bind_param('s', $like);
$stmt->execute();
$result = $stmt->get_result();

/*
|--------------------------------------------------------------------------
| Gambar produk
|--------------------------------------------------------------------------
*/
$gambarProduk = [
    'Tas Selempang' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=600&q=80',

    'Botol Minum' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=600&q=80',

    'Hoodie Basic' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=600&q=80',

    'Keyboard Mechanical' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80'
];

include 'header.php';
?>

<div class="container">

    <!-- Header Produk -->
    <div class="section-head">
        <div>
            <h1>Daftar Produk</h1>

            <p class="muted">
                Pilih produk untuk melihat detail dan menambahkannya ke keranjang.
            </p>
        </div>

        <a class="btn" href="keranjang.php">
            🛒 Keranjang (<?= cart_count() ?>)
        </a>
    </div>


    <!-- Pencarian -->
    <form class="search wide" method="get">

        <input
            type="text"
            name="q"
            value="<?= e($q) ?>"
            placeholder="Cari produk..."
        >

        <button class="btn" type="submit">
            Cari
        </button>

    </form>


    <!-- Daftar Produk -->
    <div class="grid">

        <?php if ($result->num_rows > 0): ?>

            <?php while ($p = $result->fetch_assoc()): ?>

                <?php
                /*
                | Jika database sudah mempunyai image_url,
                | gunakan image_url.
                |
                | Jika kosong, gunakan gambar berdasarkan nama produk.
                */
                $namaProduk = $p['nama_produk'];

                if (!empty($p['image_url'])) {
                    $urlGambar = $p['image_url'];
                } elseif (isset($gambarProduk[$namaProduk])) {
                    $urlGambar = $gambarProduk[$namaProduk];
                } else {
                    $urlGambar = 'https://placehold.co/600x400?text=Produk';
                }
                ?>

                <article class="card">

                    <!-- Gambar -->
                    <div class="thumb">

                        <img
                            src="<?= e($urlGambar) ?>"
                            alt="<?= e($namaProduk) ?>"
                            loading="lazy"
                        >

                    </div>


                    <!-- Informasi Produk -->
                    <div class="card-body">

                        <small>
                            <?= e($p['nama_kategori'] ?? '-') ?>
                        </small>

                        <h3>
                            <?= e($namaProduk) ?>
                        </h3>

                        <strong>
                            <?= rupiah($p['harga']) ?>
                        </strong>

                        <p class="muted">
                            Stok: <?= (int)$p['stok'] ?>
                        </p>


                        <!-- Tombol -->
                        <div style="
                            display:flex;
                            gap:10px;
                            margin-top:15px;
                        ">

                            <a
                                class="btn"
                                href="detail_produk.php?id=<?= (int)$p['id_produk'] ?>"
                            >
                                Detail
                            </a>

                            <a
                                class="btn"
                                href="keranjang.php?add=<?= (int)$p['id_produk'] ?>"
                            >
                                Beli
                            </a>

                        </div>

                    </div>

                </article>

            <?php endwhile; ?>

        <?php else: ?>

            <div style="
                grid-column:1/-1;
                text-align:center;
                padding:50px 20px;
            ">

                <h2>Produk tidak ditemukan</h2>

                <p class="muted">
                    Coba gunakan kata kunci pencarian yang berbeda.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>


<?php include 'footer.php'; ?>