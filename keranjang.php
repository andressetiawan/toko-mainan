<?php
session_start();
require './include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];
$categories = query("SELECT * FROM kat_produk");
//TAMPILKAN SEMUA YANG BELUM DIBAYAR, MASIH BISA CANCEL
$carts = query("SELECT id_transaksi,gambar_produk,nama_produk,harga_produk,jumlah FROM transaksi NATURAL JOIN produk WHERE id_user='$id_user' AND id_transaksi = ANY (SELECT id_transaksi FROM transaksi WHERE id_transaksi NOT IN(SELECT id_transaksi FROM pembayaran))");

if(isset($_POST['btn-pembayaran'])){
    echo "Baryar".$_POST['id_transaksi'];
}

if(isset($_POST['btn-cancel'])){
    if(hapus($_POST)>0){
        echo "<script> 
        console.log('Berhasil'); 
        document.location.href = 'keranjang.php';
        </script>";
    } else {
        echo "<script> console.log('Cancel beberapa saat lagi'); </script>";
    }

}
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
    <link rel="stylesheet" href="./css/keranjang.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko mainan</title>
</head>
<body>
    <header> 
        <div id="banner">
            <p>Mainan Anak - Toko Mainan - Jual Mainan - Alat Peraga Edukatif - Mainan Bayi - Mainan Kayu - Grosir Mainan - Wooden Toys</p>
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
            <a class="keranjang-nav" >Keranjang Belanja</a>
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
            <?php foreach($carts as $cart) : ?>
            <div id="product">

                <div id="gambar"?>
                <img src="./img/<?=$cart['gambar_produk']?>" alt="<?=$cart['gambar_produk']?>" width="150">
                </div>

                <div id="nama_barang">
                    <p>Nama barang</p>
                    <h3><?= $cart['nama_produk'] ?></h3>
                </div>

                <div id="harga">
                    <p>Harga</p>
                    <h3><?= rupiah($cart['jumlah']*$cart['harga_produk']) ?></h3>
                </div>

                <div id="jumlah">
                    <p>Jumlah</p>
                    <h3><?= $cart['jumlah'] ?></h3>
                </div>

                <div id="tool">
                    <form action="pembayaran.php" method="post">
                        <input type="hidden" name="id_transaksi" value="<?= $cart['id_transaksi'] ?>">
                        <button name="btn-pembayaran" type="submit">PEMBAYARAN</button>
                    </form>
                    
                    <form action="" method="post">
                        <input type="hidden" name="id_transaksi" value="<?= $cart['id_transaksi'] ?>">
                        <button name="btn-cancel" type="submit">CANCEL</button>
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