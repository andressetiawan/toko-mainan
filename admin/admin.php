<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['masuk']) {
    header('Location: ../index.php');
}

require '../include/functions.php';
$username = $_SESSION['nama'];
if(isset($_POST['btn-input'])){
    global $conn;
    if(input($_POST)>0){
        echo "<script> alert('Data berhasil diinput'); 
        document.location.href = 'admin.php';
        </script>";
    } else {
        echo mysqli_error($conn);
    }
}

if(isset($_POST['btn-delete'])){
    if(hapusProduct($_POST)>0){
        echo "<script> alert('Data berhasil dihapus'); 
        document.location.href = 'admin.php';
        </script>";
    } else {
        echo mysqli_error($conn);
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
    <link rel="stylesheet" href="../css/admin.css">
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
            <section id="input-admin">
                <form action="" method="post" enctype="multipart/form-data">
                    <h1>Administrator Dashboard</h1>

                    <label for="nama_produk">Nama produk</label>
                    <input id="nama_produk" name="nama_produk" type="text">

                    <label for="stok">Stok</label>
                    <input id="stok" name="stok" type="number">

                    <label for="harga_produk">Harga produk</label>
                    <input id="harga_produk" name="harga_produk" type="number">

                    <label for="kategori_produk">Kategori produk</label>
                    <select name="kategori_produk" id="kategori_produk">
                    <?php foreach($categories as $categorie) : ?>
                        <option value="<?= $categorie['id_kat_produk'] ?>"><?= $categorie['jenis_produk'] ?></option>
                    <?php endforeach; ?>
                    </select>
                    <label for="berat_produk">Berat produk</label>
                    <span><input id="berat_produk" name="berat_produk" type="number"> Gram</span>

                    <label for="gambar_produk">Gambar produk</label>
                    <input style="all: initial; font-family: 'Poppins', sans-serif; color: #121920;" id="gambar_produk" name="gambar_produk" type="file">

                    <label for="keterangan_produk">Keterangan produk</label>
                    <textarea name="keterangan_produk" id="keterangan_produk" style="resize: none" placeholder="Masukan keterangan dari produk"></textarea>

                    <button name="btn-input" type="submit">INPUT</button>
                </form>
            </section>

            <section id="product">
            <?php foreach($products as $product) : ?>
                <div id="box" >
                    <div id="gambar">
                        <img src="../img/<?= $product['gambar_produk'] ?>" width="165">
                    </div>
                    <div id="caption">
                        <a href="adminUpdate.php?p=<?=$product['id_produk'] ?>" ><?= $product['nama_produk'] ?></a>
                        <h3><?= rupiah($product['harga_produk']) ?></h3>
                    </div>
                    <form action="" method="post">
                        <input type="hidden" name="id_produk" value="<?= $product['id_produk'] ?>">
                        <button onclick="return confirm('Apakah anda ingin mengapus produk?'); " type="submit" name="btn-delete">X</button>
                    </form>
                </div>
            <?php endforeach; ?>
            </section>
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