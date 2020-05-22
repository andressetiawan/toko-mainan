<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['masuk']) {
    header('Location: ../index.php');
}
require '../include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];
$id_pengiriman = $_GET['q'];

$query = "SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama,no_hp_user, bukti_pembayaran,nama_produk,berat_produk,jumlah,kelurahan,kecamatan,kabupaten,provinsi,jenis_pengiriman,status_pembayaran,status_pengiriman,keterangan,pembayaran.id_transaksi FROM produk,user,pembayaran,pengiriman,kat_pengiriman,transaksi,alamat WHERE produk.id_produk = transaksi.id_produk AND user.id_user = transaksi.id_user AND user.id_alamat = alamat.id_alamat AND transaksi.id_transaksi = pengiriman.id_transaksi AND kat_pengiriman.id_kat_pengiriman = pengiriman.id_kat_pengiriman AND transaksi.id_transaksi = pembayaran.id_transaksi AND id_pengiriman = '$id_pengiriman'";
$details = query($query);

if(isset($_POST['sending'])){
    if($details[0]['status_pengiriman'] == "Sending"){
    echo "<script> alert('Barang sedang dikirim');
    document.location.href = 'adminPengiriman.php'; </script>";
    } else {
    global $conn;
    mysqli_query($conn,"UPDATE pengiriman SET status_pengiriman = 'Sending' WHERE id_pengiriman ='$id_pengiriman'");
    echo "<script> alert('Barang dikirim');
    document.location.href = 'adminPengiriman.php'; </script>";
    }
    exit;
}

if(isset($_POST['resubmit'])){
    if($details[0]['status_pembayaran'] == "Resubmit"){
    echo "<script> alert('Permintaan Resubmit Telah dilakukan');
    document.location.href = 'adminCheck.php'; </script>";
    } else {
    global $conn;
    mysqli_query($conn,"UPDATE pembayaran SET status_pembayaran = 'Resubmit' WHERE id_pembayaran ='$id_pembayaran'");
    echo "<script> alert('Permintaan Resubmit berhasil');
    document.location.href = 'adminCheck.php'; </script>";
    }
    exit;
}

if(isset($_POST['canceled'])){
    global $conn;
    $jumlah = $details[0]['jumlah'];
    $id_produk = $details[0]['id_produk'];
    $query = "UPDATE produk SET stok = stok + '$jumlah' WHERE id_produk = '$id_produk' ;";
    $query .= "DELETE FROM pembayaran WHERE id_pembayaran ='$id_pembayaran';";
    mysqli_multi_query($conn,$query);
    echo "<script> alert('Transaksi dibatalkan');
    document.location.href = 'adminCheck.php'; </script>";
    exit;
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
            <h1>CEK DATA PENGIRIMAN</h1>
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
        <form action="adminPengiriman.php" method="post">
            <label style="font-size: larger" for="transaksi"><b>CEK DATA PENGIRIMAN : </b></label> <br>
            <input type="number" name="transaksi" id="transaksi" placeholder="Masukan no transaksi" required>
            <button type="submit" name="tombol">SEARCH</button>
        </form>
        <div id="ket">
        <?php foreach($details as $detail) : ?>
            <div style="display: flex; flex-wrap: wrap;">
                <div style="margin-right: 15px;">
                    <h3>Nama Lengkap</h3>
                    <p> <?= $detail['nama'] ?> </p>
                </div>
                <div style="margin-right: 15px;">
                    <h3>No.Handphone</h3>
                    <p> <?= $detail['no_hp_user'] ?></p>
                </div>
            </div>

            <div style="display: flex; flex-wrap: wrap;">
                <div style="margin-right: 15px;">
                    <h3>Nama Produk</h3>
                    <p> <?= $detail['nama_produk'] ?> </p>
                </div>
                <div style="margin-right: 15px;">
                    <h3>Berat Produk</h3>
                    <p> <?= ($detail['berat_produk']*$detail['jumlah'])/1000 ?> KG</p>
                </div>
                <div style="margin-right: 15px;">
                    <h3>Jumlah</h3>
                    <p> <?= $detail['jumlah'] ?> </p>
                </div>
            </div>

            <div style="display: flex; flex-wrap: wrap;">
                <div style="margin-right: 15px;">
                    <h3>Kelurahan</h3>
                    <p> <?= $detail['kelurahan'] ?> </p>
                </div>
                <div style="margin-right: 15px;">
                    <h3>Kecamatan</h3>
                    <p> <?= $detail['kecamatan'] ?> </p>
                </div>
                <div style="margin-right: 15px;">
                    <h3>Kabupaten</h3>
                    <p> <?= $detail['kabupaten'] ?> </p>
                </div>
                <div style="margin-right: 15px;">
                    <h3>Provinsi</h3>
                    <p> <?= $detail['provinsi'] ?> </p>
                </div>
            </div>
            <div style="line-height: initial">
            <h3>Detail Alamat</h3>
            <p><?= $detail['keterangan'] ?> </p>
            </div>
            <h3>Jenis Pengiriman</h3>
            <p> <?= $detail['jenis_pengiriman'] ?> </p>
            <h3>Status pengiriman</h3>
            <p> <?= $detail['status_pengiriman'] ?> </p>
            <h3>Status pembayaran</h3>
            <p><?= $detail['status_pembayaran'] ?> </p>
            <h3>Bukti Pembayaran</h3>
            <a id="btn-download" href="../pembayaran/<?= $detail['bukti_pembayaran'];?>"target="_blank" download="">DOWNLOAD</a>

            <form action="" method="post">
                <button type="submit" id="sending" name="sending"> SENDING </button>
            </form>
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