<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['login']){
    header('Location: index.php');
}
require './include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];
$categories = query("SELECT * FROM kat_produk");

//TAMPILKAN PEMBAYARAN PUNYA USER
$details = query("SELECT bukti_pembayaran,pembayaran.id_transaksi,nama_produk,jenis_pembayaran,jumlah,harga_produk,status_pembayaran,jenis_pengiriman,pembayaran.id_kat_pembayaran FROM transaksi,produk,kat_pembayaran,pembayaran,pengiriman WHERE transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_produk = produk.id_produk AND transaksi.id_pengiriman = pengiriman.id_pengiriman AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC");

if(isset($_GET['q'])){
    $keyword = $_GET['q'];
    $products = query("SELECT * FROM produk WHERE id_kat_produk='$keyword'");
}
if(isset($_POST['btn-search'])){
    $keyword = $_POST['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/pemesanan.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko mainan</title>
</head>
<body>
    <header> 
        <div id="banner">
            <marquee>Mainan Anak - Toko Mainan - Jual Mainan - Alat Peraga Edukatif - Mainan Bayi - Mainan Kayu - Grosir Mainan - Wooden Toys</marquee>
            <h1>TOKO MAINAN</h1>
        </div>

        <nav>
            <a id="kat-nav" class="kat-nav">Kategori</a>
            <a href="user.php" >Home</a>
            <a class="pemesanan-nav" >Pemesanan</a>
            <!-- Searching -->
            <form action="user.php" method="post">
                <input name="search" id="search" type="text" required placeholder="Cari barang disini"> </input>
                <button name="btn-search" id="search" type="submit"><i class="fa fa-search"></i> </button>
            </form>
            <a href="keranjang.php" class="keranjang-nav" >Keranjang Belanja</a>
            <a href="./include/logout.php" >Logout</a>
            <div id="profile">
                <i id="profile" class="fa fa-user"></i>
                <p><?= $username ?></p>
            </div>
        </nav>
    </header>

    <main id="homepage">
    <aside>
        <section id="kategori-signup">
            <?php foreach($categories as $categorie) :?>
                <a href="user.php?q=<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk'] ?></a>
            <?php endforeach;?>
        </section>  
    </aside>
        <section id="main">
            <?php foreach($details as $detail) : ?>
            <div id="product">
                <div id="keterangan">
                    <h1>#<?= $detail['id_transaksi'] ?> </h1>
                    <p><em><?= $detail['nama_produk'] ?></em></p>
                    <p>Total pembayaran : <b><?= rupiah($detail['jumlah']*$detail['harga_produk']) ?></b></p>
                    <p>Jenis pembayaran : <b style="text-transform: capitalize";><?= $detail['jenis_pembayaran'] ?></b></p>
                    <p>Jenis pengiriman : <b style="text-transform: capitalize";><?= $detail['jenis_pengiriman'] ?></b></p>
                    <p>Status pembayaran : <b style="text-transform: capitalize";><?= $detail['status_pembayaran'] ?></b></p>

                </div>

                <div id="tool">
                    <form action="pembayaran.php" method="post">
                        <!-- KALAU BUKTI PEMBAYARAN ADA TIDAK USAH TAMPILKAN KONFIRMASI PEMBAYARAN -->
                        <?php if($detail['bukti_pembayaran'] == NULL && $detail['id_kat_pembayaran']=="1") : ?>
                            <input type="hidden" name="id_transaksi" value="<?= $detail['id_transaksi'] ?>">
                            <p>Transfer</p>
                            <p><b>BCA 0000-88-0000</b></p>
                            <p>a.n Darryl Nathanael</p>
                            <button name="btn-pembayaran" type="submit">KONFIRMASI PEMBAYARAN</button>
                        <?php endif; ?>

                        <?php if($detail['bukti_pembayaran'] == NULL && $detail['id_kat_pembayaran']=="2")  : ?>
                            <input type="hidden" name="id_transaksi" value="<?= $detail['id_transaksi'] ?>">
                            <p>OVO Barcode</p>
                            <img src="./img/OVO.jpg" alt="Ovo Barcode" width="100">
                            <p>a.n Darryl Nathanael</p>
                            <button name="btn-pembayaran" type="submit">KONFIRMASI PEMBAYARAN</button>
                        <?php endif; ?>

                        <?php if($detail['bukti_pembayaran'] !== NULL &&  $detail['id_kat_pembayaran']=="2" && $detail['status_pembayaran'] == "Resubmit") :?>
                            <input type="hidden" name="id_transaksi" value="<?= $detail['id_transaksi'] ?>">
                            <p>OVO Barcode</p>
                            <img src="./img/OVO.jpg" alt="Ovo Barcode" width="100">
                            <p>a.n Darryl Nathanael</p>
                            <button name="btn-pembayaran" type="submit">KONFIRMASI PEMBAYARAN</button>
                        <?php endif; ?>

                        <?php if($detail['bukti_pembayaran'] !== NULL &&  $detail['id_kat_pembayaran']=="1" && $detail['status_pembayaran'] == "Resubmit") :?>
                            <input type="hidden" name="id_transaksi" value="<?= $detail['id_transaksi'] ?>">
                            <p>Transfer</p>
                            <p><b>BCA 0000-88-0000</b></p>
                            <p>a.n Darryl Nathanael</p>
                            <button name="btn-pembayaran" type="submit">KONFIRMASI PEMBAYARAN</button>
                        <?php endif; ?>

                        <?php if($detail['bukti_pembayaran'] !== NULL) :?>
                            <h3 style="text-transform: uppercase"><?= $detail['status_pembayaran'] ?></h3>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <?php endforeach;?>
        </section>
    </main>

    <footer></footer>

    <script src="./js/script.js"></script>
    <script>
        var kat_nav = document.getElementById('kat-nav');
        var kategori = document.getElementById('kategori-signup');
        kat_nav.addEventListener('click', function(){
            kategori.classList.toggle("show");
        });
        var profile = document.querySelector('div#profile');
        profile.addEventListener('click',function(){
            document.location.href = 'profile.php';
        });
    </script>
</body>
</html>