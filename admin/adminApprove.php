<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['masuk']) {
    header('Location: ../index.php');
}
require '../include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];
$id_transaksi = $_GET['p'];

$query = "SELECT id_pembayaran,pembayaran.id_transaksi,CONCAT(nama_depan,' ',nama_belakang) AS nama, nama_produk,jumlah,harga_produk,bukti_pembayaran,jenis_pengiriman,jenis_pembayaran,status_pembayaran FROM transaksi,user,produk,kat_pembayaran,pembayaran,pengiriman,kat_produk WHERE produk.id_kat_produk = kat_produk.id_kat_produk AND transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_produk = produk.id_produk AND transaksi.id_user=user.id_user AND transaksi.id_pengiriman = pengiriman.id_pengiriman AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pembayaran.id_transaksi = '$id_transaksi'";
$details = query($query);
$id_pembayaran = $details[0]['id_pembayaran'];
if(isset($_POST['approved'])){
    if($details[0]['status_pembayaran'] == "Approved"){
    echo "<script> alert('Barang telah di approved');
    document.location.href = 'adminCheck.php'; </script>";
    } else {
    global $conn;
    mysqli_query($conn,"UPDATE pembayaran SET status_pembayaran = 'Approved' WHERE id_pembayaran ='$id_pembayaran'");
    echo "<script> alert('Barang berhasil di approved');
    document.location.href = 'adminCheck.php'; </script>";
    }
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
    <link rel="stylesheet" href="../css/adminApprove.css">
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
        <form action="adminCheck.php" method="post">
            <label style="font-size: larger" for="transaksi"><b>Masukan no transaksi : </b></label> <br>
            <input type="number" name="transaksi" id="transaksi" placeholder="Masukan no transaksi">
            <button type="submit" name="tombol">SEARCH</button>
        </form>
        <div id="ket">
        <?php foreach($details as $detail) : ?>
            <h3>ID Transaksi</h3>
            <p ><?= $detail['id_transaksi'] ?></p>
            <h3>Nama Lengkap</h3>
            <p> <?= $detail['nama'] ?> </p>
            <h3>Nama Produk</h3>
            <p> <?= $detail['nama_produk'] ?> </p>
            <h3>Total Harga</h3>
            <p><?= rupiah($detail['jumlah']*$detail['harga_produk']) ?> </p>
            <h3>Jenis Pengiriman</h3>
            <p> <?= $detail['jenis_pengiriman'] ?> </p>
            <h3>Jenis Pembayaran</h3>
            <p> <?= $detail['jenis_pembayaran'] ?> </p>
            <h3>Bukti Pembayaran</h3>
            <?php if(is_null($detail['bukti_pembayaran'])) : ?>
                <a href="../img/no_photo.png" target="_blank"><img src="../img/no_photo.png" width="120"></a>
            <?php else : ?>
                <a href="../pembayaran/<?= $detail['bukti_pembayaran'];?>" target="_blank"><img src="../pembayaran/<?= $detail['bukti_pembayaran'];?>" width="120"></a>
                <a style="text-decoration: none; margin-top:10px;color:#2c3e50; font-weight: bold;" 
                href="../pembayaran/<?= $detail['bukti_pembayaran'];?>" 
                target="_blank" download="">DOWNLOAD</a>
                <form action="" method="post">
                <button style="margin-top: 10px" type="submit" name="approved"> APPROVED </button>
            </form>
            <?php endif; ?>
        <?php endforeach; ?>   
        </div>                                                            
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