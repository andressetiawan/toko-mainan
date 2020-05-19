<?php
session_start();
//APAKAH SUDAH LOGIN?
if(isset($_SESSION['login'])){
    header('Location: user.php');
} 
else if (isset($_SESSION['masuk'])) {
    header('Location: admin.php');
}
require 'include/functions.php';
//TAMPILKAN SEMUA KATEGORI
$categories = query("SELECT * FROM kat_produk");
//TAMPILKAN SEMUA PRODUK
if(!isset($_GET['q']) || !isset($_POST['btn-search'])){
    $products = query("SELECT*FROM produk WHERE status_produk = 'Ready'");
}
//TAMPILKAN SEMUA PRODUK BERDASARKAN ID_KAT_PRODUK
if(isset($_GET['q'])){
    $keyword = $_GET['q'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$id' AND status_produk = 'Ready'");
}
//TAMPILKAN SEMUA PRODUK BERDASARKAN NAMA PRODUK
if(isset($_POST['btn-search'])){
    $keyword = $_POST['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' AND status_produk = 'Ready'");
}
//LOGIN SYSTEM
if(isset($_POST['login'])){
    global $conn;
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username ='$username'");
    if(mysqli_num_rows($result)==1){
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password,$user['password'])){
          if($user['id_kat_user']==1){
            $_SESSION['nama'] = $user['username'];
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['masuk'] = true;
            echo "
            <script> 
              alert('login berhasil');
              document.location.href = 'admin/admin.php';
            </script>
            ";
          } else {
            $_SESSION['nama'] = $user['username'];
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['login'] = true;
            echo "
            <script> 
              alert('login berhasil');
              document.location.href = './user.php';
            </script>
            ";
          }
        } else {
          echo "<script> alert('Username/Password salah')</script>";
        }
    } else {
      echo "<script> alert('Username/Password salah')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko mainan</title>
</head>
<body>
    <header> 
        <!-- TULISAN PALING ATAS -->
        <div id="banner">
        <marquee>Mainan Anak - Toko Mainan - Jual Mainan - Alat Peraga Edukatif - Mainan Bayi - Mainan Kayu - Grosir Mainan - Wooden Toys</marquee>
            <h1>TOKO MAINAN</h1>
        </div>

        <!-- NAVIGATION BAR -->
        <nav>
            <a id="kat-nav" class="kat-nav">Kategori</a>
            <a href="index.php">Home</a>
            <a class="pemesanan-nav" onclick="alert('Login sebelum melakukan transaksi')" >Pemesanan</a>
            <!-- SEARHCING DATA BERDASARKAN NAMA -->
            <form action="" method="post">
                <input name="search" id="search" type="text" placeholder="Cari barang disini" required></input>
                <button name="btn-search" id="search" type="submit"><i class="fa fa-search"></i></button>
            </form>
            <a class="keranjang-nav" onclick="alert('Login sebelum melakukan transaksi')" >Keranjang Belanja</a>
            <a href="signup.php" >Daftar</a>
        </nav>
    </header>

    <!-- SETELAH BANNER DI ATAS -->
    <main id="homepage">
        <!-- KATEGORI KIRI KLIK -->
        <aside>
            <section id="kategori-index">
                <?php foreach($categories as $categorie) :?>
                    <a href="index.php?q=<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk'] ?></a>
                <?php endforeach;?>
            </section>  
            <!-- FORMULIR LOGIN KIRI -->
            <section id="login">
                <form action="" method="post">
                    <h1> Username </h1>
                    <input type="text" name="username" id="username">
                    <h1> Password </h1>
                    <input type="password" name="password" id="password"> <br>
                    <button type="submit" name="login" id="login"> LOGIN </button>
                </form>
            </section>
        </aside>

        <!-- MAIN AREA KANAN -->
        <section id="main">
            <!-- TULISAN SELAMAT DATANG -->
            <div id="hello">
                <h1>Selamat datang ke toko mainan</h1>
                <p>Kami menyediakan segala macam mainan mulai dari anak-anak sampai remaja, dengan harga yang murah dan kualitas terjamin bagus. Silahkan daftar akun terlebih dahulu sebelum melakukan transaksi.</p>
            </div>
            <!-- TAMPILKAN SEMUA PRODUK -->
            <div id="product">
                <?php foreach($products as $product) : ?>
                    <div id="box" >
                        <div id="gambar">
                            <img src="img/<?= $product['gambar_produk'] ?>" width="160">
                        </div>
                        <div id="caption">
                            <a href="detail.php?p=<?=$product['id_produk'] ?>" ><?= $product['nama_produk'] ?></a>
                            <h3><?= rupiah($product['harga_produk']) ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script src="./js/script.js"></script>
    <script>
        var kat_nav = document.getElementById('kat-nav');
        var kategori = document.getElementById('kategori-index');
        kat_nav.addEventListener('click', function(){
            kategori.classList.toggle("show");
        })
    </script>
</body>
</html>