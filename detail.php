<?php
session_start();
//APAKAH SUDAH LOGIN?
if(isset($_SESSION['login'])){
    header('Location: user.php');
} 
else if (isset($_SESSION['masuk'])) {
    header('Location: admin/admin.php');
}
require 'include/functions.php';
// SEMUA KAT_PRODUK YANG ADA
$categories = query("SELECT * FROM kat_produk");
// GABUNGAN PRODUK DENGAN KAT_PRODUK
$id = $_GET['p'];
$products = query("SELECT*FROM produk NATURAL JOIN kat_produk WHERE id_produk = '$id'");
//SEARCH BERDASARKAN KATEGORI
if(isset($_GET['q'])){
    $keyword = $_GET['q'];
    $products = query("SELECT * FROM produk WHERE id_kat_produk='$keyword'");
}
//SEARCH BERDASARKAN NAMA_PRODUK
if(isset($_POST['btn-search'])){
    $keyword = $_POST['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%'");
}
//Login system
if(isset($_POST['login'])){
    global $conn;
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username ='$username'");
    if(mysqli_num_rows($result)==1){
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password,$user['password'])){
          if($user['id_kat_user']==1){
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['username'];
            echo "
            <script> 
              alert('login berhasil');
              document.location.href = './admin.php';
            </script>
            ";
          } else {
            $_SESSION['nama'] = $user['username'];
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id_user'];
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
    <link rel="stylesheet" href="./css/detail.css">
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
            <a href="index.php">Home</a>
            <a class="pemesanan-nav" onclick="alert('Login sebelum melakukan transaksi')" >Pemesanan</a>
            <!-- Searching -->
            <form action="index.php" method="post">
                <input name="search" id="search" type="text" required placeholder="Cari barang disini"> </input>
                <button name="btn-search" id="search" type="submit"><i class="fa fa-search"></i> </button>
            </form>
            <a class="keranjang-nav" onclick="alert('Login sebelum melakukan transaksi')" >Keranjang Belanja</a>
            <a href="signup.php" >Daftar</a>
        </nav>
    </header>

    <main id="homepage">
        <!-- Kategori kiri -->
        <aside>
            <section id="kategori-index">
                <?php foreach($categories as $categorie) :?>
                    <a href="index.php?q=<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk'] ?></a>
                <?php endforeach;?>
            </section>  

            <!-- Login form -->
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

        <section id="main">
            <!-- SEMUA KETERANGAN PRODUK -->
            <?php foreach($products as $product) :?>
            <img src="./img/<?= $product['gambar_produk'] ?>" alt="<?= $product['gambar_produk'] ?>" width="200">
            <h2><?= $product['nama_produk'] ?></h2>
            <h3>Keterangan Produk</h3>
            <div style="line-height: initial" id="ket">
                <p><?php echo $product['keterangan_produk'] ?></p>
            </div>
            <h5>Harga Produk </h5> <h3><?= rupiah($product['harga_produk']) ?></h3>
            <h5>Kategori Produk </h5> <h3><?= $product['jenis_produk'] ?></h3>
            <h5>Stok Produk </h5> <h3><?= $product['stok'] ?></h3>
            <h5>Berat Produk </h5> <h3><?= $product['berat_produk'].' gram' ?></h3>
            <?php endforeach;?>
            <button onclick="alert('Login sebelum melakukan transaksi')">Beli</button>
        </section>
            
    </main>

    <footer></footer>

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