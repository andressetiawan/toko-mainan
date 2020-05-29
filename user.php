<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['login']){
    header('Location: index.php');
}
require 'include/functions.php';
$username = $_SESSION['nama'];
$categories = query("SELECT * FROM kat_produk");

if(!isset($_GET['q']) || !isset($_POST['btn-search'])){
    $products = query("SELECT*FROM produk WHERE status_produk = 'Ready'");
    $halaman = count($products);
}
if(isset($_GET['q'])){
    $id = $_GET['q'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$id' AND status_produk = 'Ready'");
    $halaman = count($products);
}
if(isset($_POST['btn-search'])){
    $keyword = $_POST['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' AND status_produk = 'Ready'");
    $halaman = count($products);
}
if(isset($_POST['btn-lihat'])){
    $hargaMin = $_POST['hargaMin'];
    $hargaMax = $_POST['hargaMax'];
    if($hargaMax < $hargaMin){
        echo "<script> alert('Harga maximum harus lebih besar dari harga minimum'); </script>";
    } else {
    $products = query("SELECT * FROM produk WHERE harga_produk BETWEEN '$hargaMin' AND '$hargaMax'");
    $halaman = count($products);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/user.css">
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
            <a href="pemesanan.php"class="pemesanan-nav" >Pemesanan</a>
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
    <section id="tools">
            <form action="" method="post">
                <label>Harga Minimum </label>
                <input name="hargaMin" type="number" required> </input>
                <label>Harga Maximum </label>
                <input name="hargaMax" type="number" required> </input>
                <button type="submit" name="btn-lihat" id="btn-lihat" > SEARCH </button>
            </form>
        </section>

        <section id="kategori-signup">
            <?php foreach($categories as $categorie) :?>
                <a href="user.php?q=<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk'] ?></a>
            <?php endforeach;?>
        </section>

        <section id="product">
        <?php if($halaman === 0) : ?>
            <div style="margin-top: 50%;">
                <h1 style="color:#e17055; text-shadow: 2px 3px 5px black;">Produk tidak tersedia</h1>
            </div>
        <?php endif; ?>
        <?php foreach($products as $product) : ?>
            <div id="box" >
                <div id="gambar">
                    <img src="./img/<?= $product['gambar_produk'] ?>" width="165">
                </div>
                <div id="caption">
                    <a href="userdetail.php?p=<?=$product['id_produk'] ?>" ><?= $product['nama_produk'] ?></a>
                    <h3><?= rupiah($product['harga_produk']) ?></h3>
                </div>
            </div>
        <?php endforeach; ?>
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