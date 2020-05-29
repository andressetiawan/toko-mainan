<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['masuk']) {
    header('Location: ../index.php');
}
require '../include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];

if(isset($_POST['tombol']) && isset($_POST['transaksi'])){
    $id_transaksi = $_POST['transaksi'];
    $query = "SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama,id_pengiriman,id_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,jenis_pengiriman,status_pengiriman,status_pembayaran FROM transaksi,user,produk,pengiriman,kat_pengiriman,pembayaran WHERE transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_transaksi = pengiriman.id_transaksi AND transaksi.id_user = user.id_user AND transaksi.id_produk = produk.id_produk AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND status_pembayaran = 'Approved' AND pembayaran.id_transaksi = '$id_transaksi' ORDER BY id_pembayaran DESC";
    $details = query($query);
    $halaman = count($details);
}
else {
    $query = "SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama,id_pengiriman,id_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,jenis_pengiriman,status_pengiriman,status_pembayaran FROM transaksi,user,produk,pengiriman,kat_pengiriman,pembayaran WHERE transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_transaksi = pengiriman.id_transaksi AND transaksi.id_user = user.id_user AND transaksi.id_produk = produk.id_produk AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND status_pembayaran = 'Approved' ORDER BY id_pembayaran DESC;";
    $details = query($query);
    $halaman = count($details);
}
//SEARCH
$categories = query("SELECT * FROM kat_produk");
if(!isset($_GET['q']) || !isset($_POST['btn-search'])){
    $products = query("SELECT*FROM produk ORDER BY id_produk DESC LIMIT 0,3");
}
if(isset($_GET['q'])){
    $id = $_GET['q'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$id' ORDER BY id_produk DESC");
}
if(isset($_POST['btn-search'])){
    $keyword = $_POST['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' ORDER BY id_produk DESC");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/adminCheck.css">
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
            <a href="admin.php" >Home</a>
            <a href="adminCheck.php"class="pemesanan-nav" >Cek Pemesanan</a>
            <!-- Searching -->  
            <form action="admin.php" method="post">
                <input name="search" id="search" type="text" required placeholder="Cari barang disini"> </input>
                <button name="btn-search" id="search" type="submit"><i class="fa fa-search"></i> </button>
            </form>
            <a href="adminPengiriman.php" class="keranjang-nav" >Cek pengiriman</a>
            <a href="../include/logout.php" >Logout</a>
            <div id="profile">
                <i id="profile" class="fa fa-user"></i>
                <p><?= $username ?></p>
            </div>
        </nav>
    </header>

    <main id="homepage">
            <section id="kategori-signup">
                <?php foreach($categories as $categorie) :?>
                    <a href="admin.php?q=<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk'] ?></a>
                <?php endforeach;?>
            </section>
        <section id="container-admin">
        <form action="" method="post">
            <label style="font-size: larger" for="transaksi"><b>CEK DATA PENGIRIMAN : </b></label> <br>
            <input type="number" name="transaksi" id="transaksi" placeholder="Masukan no transaksi" required>
            <button type="submit" name="tombol">SEARCH</button>
        </form>
        <table>
            <thead>
                <th>ID Transaksi</th>
                <th>Nama Lengkap</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Jenis Pengiriman</th>
                <th>Status Pengiriman</th>
            </thead>

            <tbody>
                <?php foreach($details as $detail) : ?>
                    <tr onclick="document.location.href = 'adminKirim.php?q=<?= $detail['id_pengiriman']?>'">
                        <td> <?= $detail['id_transaksi'] ?> </td>
                        <td> <?= $detail['nama'] ?> </td>
                        <td> <?= $detail['nama_produk'] ?> </td>
                        <td> <?= $detail['jumlah'] ?> </td>
                        <td> <?= $detail['jenis_pengiriman'] ?> </td>
                        <td> <b style="text-transform: uppercase"><?= $detail['status_pengiriman'] ?></b></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
        </table>      
                
                <?php if($halaman === 0) : ?>
                    <div id="blank">
                        <i class="fa fa-ban	"></i> <h1> Pengiriman tidak ditemukan</h1>
                    </div>
                <?php endif; ?>
        </section>
    </main>

    <footer></footer>

    <script src="../js/script.js"></script>
    <script>
        var kat_nav = document.getElementById('kat-nav');
        var kategori = document.getElementById('kategori-signup');
        kat_nav.addEventListener('click', function(){
            kategori.classList.toggle("show");
        });
        var profile = document.querySelector('div#profile');
        profile.addEventListener('click',function(){
            document.location.href = 'adminProfile.php';
        });
    </script>
</body>
</html>