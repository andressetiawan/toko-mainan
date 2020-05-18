<?php
session_start();
require './include/functions.php';
$categories = query("SELECT * FROM kat_produk");
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];
$id = $_GET['p'];
$products = query("SELECT*FROM produk NATURAL JOIN kat_produk WHERE id_produk = '$id'");

if( isset($_POST['btn-beli']) ){
    global $conn;
    if(simpan($_POST) > 0){
        echo '
        <script>
        console.log("Berhasil");
        document.location.href = "keranjang.php";
        </script>
        ';
        exit;
    } else {
        echo mysqli_error($conn);
        exit;
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
    <link rel="stylesheet" href="./css/detail.css">
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
            <a class="pemesanan-nav" >Pemesanan</a>
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
    <aside>
        <section id="kategori-signup">
            <?php foreach($categories as $categorie) :?>
                <a href="user.php?q=<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk'] ?></a>
            <?php endforeach;?>
        </section>  
    </aside>

        <section id="main">
            <?php foreach($products as $product) :?>
            <img src="./img/<?= $product['gambar_produk'] ?>" alt="<?= $product['gambar_produk'] ?>" width="200">
            <h2><?= $product['nama_produk'] ?></h2>
            <h3>Keterangan Produk</h3>
            <p><?php echo $product['keterangan_produk'] ?></p>
            <h5>Harga Produk </h5> <h3><?= rupiah($product['harga_produk']) ?></h3>
            <h5>Kategori Produk </h5> <h3><?= $product['jenis_produk'] ?></h3>
            <h5>Stok Produk </h5> <h3><?= $product['stok'] ?></h3>
            <h5>Berat Produk </h5> <h3><?= $product['berat_produk'].' gram' ?></h3>
            <?php endforeach;?>
            <?php foreach($products as $product) :?>
            <form action="" method="post">
                <input type="hidden" name="id_user" value="<?=$id_user;?>">
                <input type="hidden" name="id_produk" value="<?=$product['id_produk']?>">
                <h5>Jumlah</h5>
                <input type="number" name="jumlah" value="1">
                <button name="btn-beli" id="btn-beli">Beli</button>
            </form>
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
        })
        var profile = document.querySelector('div#profile');
        profile.addEventListener('click',function(){
            document.location.href = 'profile.php';
        });
    </script>
</body>
</html>