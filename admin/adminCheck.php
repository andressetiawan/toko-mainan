<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['masuk']) {
    header('Location: ../index.php');
}
require '../include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];

if(isset($_POST['tombol'])){
    var_dump($_POST);
    //KALAU UDAH KASIH BUKTI PEMBAYARAN
    if($_POST['filter'] == 1){
        $query = "SELECT pembayaran.id_transaksi,CONCAT(nama_depan,' ',nama_belakang) AS nama, nama_produk,jumlah,harga_produk,jenis_produk,jenis_pengiriman,jenis_pembayaran,status_pembayaran,bukti_pembayaran FROM transaksi,user,produk,kat_pembayaran,pembayaran,pengiriman,kat_produk WHERE produk.id_kat_produk = kat_produk.id_kat_produk AND transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_produk = produk.id_produk AND transaksi.id_user=user.id_user AND transaksi.id_pengiriman = pengiriman.id_pengiriman AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND bukti_pembayaran IS NOT NULL";
        $details = query($query);
    }
    //KALAU MASIH CHECKING
    else if($_POST['filter'] == 2){
        $query = "SELECT pembayaran.id_transaksi,CONCAT(nama_depan,' ',nama_belakang) AS nama, nama_produk,jumlah,harga_produk,jenis_produk,jenis_pengiriman,jenis_pembayaran,status_pembayaran,bukti_pembayaran FROM transaksi,user,produk,kat_pembayaran,pembayaran,pengiriman,kat_produk WHERE produk.id_kat_produk = kat_produk.id_kat_produk AND transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_produk = produk.id_produk AND transaksi.id_user=user.id_user AND transaksi.id_pengiriman = pengiriman.id_pengiriman AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND status_pembayaran = 'Approved'";
        $details = query($query);
    }
    //KALAU DAH DI APPROVED
    else if($_POST['filter'] == 3){
        $query = "SELECT pembayaran.id_transaksi,CONCAT(nama_depan,' ',nama_belakang) AS nama, nama_produk,jumlah,harga_produk,jenis_produk,jenis_pengiriman,jenis_pembayaran,status_pembayaran,bukti_pembayaran FROM transaksi,user,produk,kat_pembayaran,pembayaran,pengiriman,kat_produk WHERE produk.id_kat_produk = kat_produk.id_kat_produk AND transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_produk = produk.id_produk AND transaksi.id_user=user.id_user AND transaksi.id_pengiriman = pengiriman.id_pengiriman AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND status_pembayaran = 'Checking'";
        $details = query($query);
    }

    else {
        header("Location: adminCheck.php");
    }
} else {
    $query = "SELECT pembayaran.id_transaksi,CONCAT(nama_depan,' ',nama_belakang) AS nama, nama_produk,jumlah,harga_produk,jenis_produk,jenis_pengiriman,jenis_pembayaran,status_pembayaran FROM transaksi,user,produk,kat_pembayaran,pembayaran,pengiriman,kat_produk WHERE produk.id_kat_produk = kat_produk.id_kat_produk AND transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_produk = produk.id_produk AND transaksi.id_user=user.id_user AND transaksi.id_pengiriman = pengiriman.id_pengiriman AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran";
$details = query($query);
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
            <a href="keranjang.php" class="keranjang-nav" >Keranjang Belanja</a>
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
            <input type="radio" name="filter" id="bukti" value="1"> Bukti pembayaran
            <input type="radio" name="filter" id="approve" value="2" > Approved
            <input type="radio" name="filter" id="check" value="3" > Checking <br>
            <input type="text" name="transaksi" id="transaksi">
            <button type="submit" name="tombol">SEARCH</button>
        </form>
            <table>
                <h2>Data transaksi</h2>
                <thead>
                    <th>ID Transaksi</th>
                    <th>Nama Lengkap</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th style="width: 150px">Harga Produk</th>
                    <th>Jenis Produk</th>
                    <th>Jenis Pengiriman</th>
                    <th>Jenis Pembayaran</th>
                    <th>Status Pembayaran</th>
                </thead>
                <tbody>
                    <?php foreach($details as $detail) : ?>
                    <tr>
                        <td > <a href="adminApprove.php" target="_blank"><?= $detail['id_transaksi'] ?></a> </td>
                        <td> <?= $detail['nama'] ?> </td>
                        <td> <?= $detail['nama_produk'] ?> </td>
                        <td> <?= $detail['jumlah'] ?> </td>
                        <td style="width: 150px"> <?= rupiah($detail['harga_produk']) ?> </td>
                        <td> <?= $detail['jenis_produk'] ?> </td>
                        <td> <?= $detail['jenis_pengiriman'] ?> </td>
                        <td> <?= $detail['jenis_pembayaran'] ?> </td>
                        <td> <b style="text-transform: uppercase"><?= $detail['status_pembayaran'] ?></b> </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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