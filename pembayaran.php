<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['login']){
    header('Location: index.php');
}
require 'include/functions.php';
$tanggal_sekarang = date('Y-m-d');
$username = $_SESSION['nama'];
$id = $_SESSION['id_user'];
$userData = query("SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama,metode_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk FROM user,transaksi,produk,pembayaran WHERE user.id_user=transaksi.id_user AND transaksi.id_produk = produk.id_produk AND transaksi.id_transaksi=pembayaran.id_transaksi AND transaksi.id_user=$id");

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
if(isset($_POST['btn-confirm'])){
    global $conn;
    if(updatePembayaran($_POST)>0){
        echo '<script> 
        alert("Terima kasih atas pembayaran, admin kami akan segera memproses");
        document.location.href = "pemesanan.php";
        </script>';
    } else {
        echo mysqli_error($conn);
    }
}

$addresses = query("SELECT * FROM alamat LIMIT 5");
if(isset($_POST['btn-daftar'])){
    if(signup($_POST) > 0){
        echo '<script> 
        alert("Registrasi berhasil") 
        document.location.href = "index.php";
        </script>';
    } else {
        echo '<script> 
        alert("Registrasi gagal") 
        document.location.href = "signup.php";
        </script>';
    }
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
                    <form action="" method="post" enctype="multipart/form-data">
                        <p>Tanggal pembayaran : </p>
                        <input type="date" name="tgl_pembayaran" id="tgl_pembayaran" value="<?= $tanggal_sekarang ?>" readonly>
                        <p>Nama produk : </p>
                        <input type="text" name="nama" id="nama_produk" value="<?= $userData[0]['nama_produk'] ?>" readonly>

                        <p>No Transaksi : </p>
                        <input type="text" name="id" value="<?= $userData[0]['id_transaksi'] ?>" readonly>

                        <p>Nama pembeli : </p>
                        <input style="text-transform: capitalize" type="text" name="nama_user" value="<?= $userData[0]['nama'] ?>" readonly>

                        <p>Jenis pembayaran : </p>
                        <input style="text-transform: uppercase" type="text" name="metode" value="<?= $userData[0]['metode_pembayaran'] ?>" readonly>

                        <p>Total yang harus dibayar : </p>
                        <input type="text" name="harga" value="<?= rupiah($userData[0]['jumlah']*$userData[0]['harga_produk']) ?>" readonly>

                        <p>Upload Bukti Pmebayaran disini : </p>
                        <input type="file" name="bukti" id="bukti">
                        <br>
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