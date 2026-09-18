<?php
require_once 'functions.php';

$title = 'Produk';
$q = trim($_GET['q'] ?? '');

/* =========================
   AMBIL DATA PRODUK
   Mouse tidak ditampilkan
   ========================= */
$stmt = $koneksi->prepare("
    SELECT
        p.*,
        k.nama_kategori
    FROM produk p
    LEFT JOIN kategori k
        ON k.id_kategori = p.id_kategori
    WHERE p.nama_produk LIKE ?
      AND p.nama_produk NOT LIKE '%Mouse%'
    ORDER BY p.id_produk DESC
");

$like = "%$q%";
$stmt->bind_param('s', $like);
$stmt->execute();
$result = $stmt->get_result();

/* =========================
   GAMBAR PRODUK
   ========================= */
$gambarProduk = [

    'Tas Selempang' =>
        'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=85',

    'Botol Minum' =>
        'https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=800&q=85',

    'Hoodie Basic' =>
        'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=800&q=85',

    'Keyboard Mechanical' =>
        'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=85',

'Wireless Headset' =>
    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=85'
];

include 'header.php';
?>

<style>

/* =========================
   CONTAINER
   ========================= */

.container {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 35px 40px;
    box-sizing: border-box;
}


/* =========================
   HEADER PRODUK
   ========================= */

.section-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.section-head h1 {
    margin-bottom: 8px;
}


/* =========================
   SEARCH
   ========================= */

.search.wide {
    display: flex;
    gap: 12px;
    width: 100%;
    margin-bottom: 30px;
}

.search.wide input {
    flex: 1;
    min-width: 0;
    padding: 14px 16px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 16px;
    box-sizing: border-box;
}

.search.wide .btn {
    flex-shrink: 0;
}


/* =========================
   GRID
   ========================= */

.grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 20px;
    width: 100%;
}


/* =========================
   CARD
   ========================= */

.card {
    width: 100%;
    min-width: 0;
    overflow: hidden;
    box-sizing: border-box;

    background: #fff;

    border: 1px solid #e5e5e5;
    border-radius: 18px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}


/* =========================
   GAMBAR
   ========================= */

.thumb {
    width: 100%;
    height: 210px;

    background: #f5f5f5;

    display: flex;
    align-items: center;
    justify-content: center;

    overflow: hidden;
}

.thumb img {
    width: 100%;
    height: 100%;

    /*
     * CONTAIN = seluruh gambar terlihat
     * sehingga gambar tidak terpotong
     */
    object-fit: contain;

    display: block;
}


/* =========================
   ISI CARD
   ========================= */

.card-body {
    padding: 18px;
    box-sizing: border-box;
}

.card-body small {
    display: block;
    margin-bottom: 8px;
    color: #777;
}

.card-body h3 {
    margin: 8px 0;

    font-size: 18px;
    line-height: 1.35;

    min-height: 49px;
}

.card-body strong {
    display: block;

    font-size: 18px;

    margin: 12px 0;
}

.card-body p {
    margin: 10px 0 16px;
}


/* =========================
   TOMBOL
   ========================= */

.product-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.product-buttons .btn {
    flex: 1;
    text-align: center;
    min-width: 80px;
    box-sizing: border-box;
}


/* =========================
   TABLET
   ========================= */

@media (max-width: 1200px) {

    .grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

}


/* =========================
   TABLET KECIL
   ========================= */

@media (max-width: 900px) {

    .grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .container {
        padding: 25px;
    }

}


/* =========================
   HP
   ========================= */

@media (max-width: 650px) {

    .grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .container {
        padding: 15px;
    }

    .thumb {
        height: 160px;
    }

    .section-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .search.wide {
        flex-direction: column;
    }

    .search.wide .btn {
        width: 100%;
    }

}


/* =========================
   HP SANGAT KECIL
   ========================= */

@media (max-width: 450px) {

    .grid {
        grid-template-columns: 1fr;
    }

    .thumb {
        height: 200px;
    }

}

</style>


<div class="container">

    <!-- =========================
         JUDUL
         ========================= -->

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


    <!-- =========================
         PENCARIAN
         ========================= -->

    <form class="search wide" method="get">

        <input
            type="text"
            name="q"
            value="<?= e($q) ?>"
            placeholder="Cari produk..."
        >

        <button
            class="btn"
            type="submit"
        >
            Cari
        </button>

    </form>


    <!-- =========================
         PRODUK
         ========================= -->

    <div class="grid">

        <?php if ($result->num_rows > 0): ?>

            <?php while ($p = $result->fetch_assoc()): ?>

                <?php

                $namaProduk = $p['nama_produk'];

                /*
                 * Prioritas gambar:
                 * 1. image_url database
                 * 2. gambar berdasarkan nama produk
                 * 3. gambar cadangan
                 */

                if (!empty($p['image_url'])) {

                    $urlGambar = $p['image_url'];

                } elseif (isset($gambarProduk[$namaProduk])) {

                    $urlGambar = $gambarProduk[$namaProduk];

                } else {

                    $urlGambar =
                        'https://placehold.co/800x500?text=Produk';

                }

                ?>


                <!-- =========================
                     CARD PRODUK
                     ========================= -->

                <article class="card">

                    <!-- GAMBAR -->
                    <div class="thumb">

                        <img
                            src="<?= e($urlGambar) ?>"
                            alt="<?= e($namaProduk) ?>"
                            loading="lazy"
                        >

                    </div>


                    <!-- INFORMASI -->
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


                        <!-- TOMBOL -->
                        <div class="product-buttons">

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

            <div
                style="
                    grid-column: 1 / -1;
                    text-align: center;
                    padding: 50px 20px;
                "
            >

                <h2>Produk tidak ditemukan</h2>

                <p class="muted">
                    Coba gunakan kata kunci yang berbeda.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>


<?php include 'footer.php'; ?>