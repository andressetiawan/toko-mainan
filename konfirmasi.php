<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['login']){
    header('Location: index.php');
}
if(!$_POST['id_transaksi']){
    header('Location: keranjang.php');
}
require 'include/functions.php';
//PEMBAYARAN
$payments = query("SELECT * FROM kat_pembayaran");
//PENGIRIMAN
$senders = query("SELECT * FROM kat_pengiriman");
$tanggal_sekarang = date('Y-m-d');
$username = $_SESSION['nama'];
$id = $_SESSION['id_user'];

$id_transaksi = $_POST['id_transaksi'];
//DETAIL YANG INGIN DITAMPILKAN
$details = query("SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama,id_transaksi,gambar_produk,nama_produk,jumlah,harga_produk,stok,produk.id_produk FROM transaksi,produk,user WHERE transaksi.id_produk=produk.id_produk AND transaksi.id_user=user.id_user AND transaksi.id_user='$id' AND id_transaksi='$id_transaksi'");

$categories = query("SELECT * FROM kat_produk");
if(!isset($_GET['q']) || !isset($_POST['btn-search'])){
    $products = query("SELECT*FROM produk");
}
if(isset($_GET['q'])){
    $id = $_GET['q'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$id'");
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
    <link rel="stylesheet" href="./css/pembayaran.css">
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
            <a href="pemesanan.php" class="pemesanan-nav" >Pemesanan</a>
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
            <section id="kategori-signup">
                <?php foreach($categories as $categorie) :?>
                    <a href="user.php?q=<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk'] ?></a>
                <?php endforeach;?>
            </section>

            <section id="konfirmasi-pembayaran">
                    <form action="./include/metode.php" method="post">
                    <input type="hidden" name="id_produk" id="id_produk" value="<?=$details[0]['id_produk']?>">    
                    <input type="hidden" name="stok" id="stok" value="<?=$details[0]['stok']?>">
                    <input type="hidden" name="jumlah" id="jumlah" value="<?=$details[0]['jumlah']?>">

                        <h4>Gambar </h4>
                        <img src="./img/<?=$details[0]['gambar_produk'] ?>" alt="<?=$details[0]['nama_produk'] ?>" width="150">

                        <h4>Nama produk </h4>
                        <?=$details[0]['nama_produk'] ?>

                        <h4>Jumlah</h4>
                        <?=$details[0]['jumlah'] ?>

                        <h4>Total harga</h4>
                        <?=rupiah($details[0]['jumlah']*$details[0]['harga_produk'])?>
                        <input type="hidden" name="id_transaksi" value="<?=$details[0]['id_transaksi']?>">
                        <h4>Jenis pembayaran : </h4>
                        <select name="metode" id="jenis">
                        <?php foreach($payments as $payment) : ?>
                            <option value="<?=$payment['id_kat_pembayaran']?>"><?=$payment['jenis_pembayaran']?></option>
                            <?php endforeach;?>
                        </select> <br>

                        <h4>Jenis pengiriman : </h4>
                        <select name="pengiriman" id="jenis">
                            <?php foreach($senders as $sender) : ?>
                            <option value="<?=$sender['id_kat_pengiriman']?>"><?=$sender['jenis_pengiriman']?></option>
                            <?php endforeach;?>
                        </select>
                        <h4>Detail alamat : </h4>
                        <div>
                        </div>
                        <textarea name="alamat" id="alamat" cols="50" rows="5" placeholder="Masukan nama jalan, nomor rumah, dst" required></textarea>
                        <button type="submit" name="btn-confirm" id="btn-confirm" > Confirm </button>
                    </form>
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