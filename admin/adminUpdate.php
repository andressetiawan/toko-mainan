<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['masuk']) {
    header('Location: ../index.php');
}

require '../include/functions.php';
$username = $_SESSION['nama'];
$id_produk = $_GET['p'];
$items = query("SELECT * FROM produk WHERE id_produk='$id_produk'");

if(isset($_POST['btn-update'])){
    global $conn;
    if(updateProduk($_POST)>0){
        echo "<script> alert('Data berhasil diupdate'); 
        document.location.href = 'admin.php';
        </script>";
        exit;
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
                    <input type="hidden" name="id_produk" value="<?= $_GET['p']?>">
                    <label for="nama_produk">Nama produk</label>
                    <input id="nama_produk" name="nama_produk" type="text" value="<?= $items[0]['nama_produk'] ?>">

                    <label for="stok">Stok</label>
                    <input id="stok" name="stok" type="number" value="<?= $items[0]['stok'] ?>">

                    <label for="harga_produk">Harga produk</label>
                    <input id="harga_produk" name="harga_produk" type="number" value="<?= $items[0]['harga_produk'] ?>">

                    <label for="kategori_produk">Kategori produk</label>
                    <select name="kategori_produk" id="kategori_produk">
                    <?php foreach($categories as $categorie) : ?>
                        <?php if($items[0]['id_kat_produk']==$categorie['id_kat_produk']) { ?>
                            <option value="<?= $categorie['id_kat_produk']?>" selected><?= $categorie['jenis_produk']?></option>
                        <?php } else { ?>
                        <option value="<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk']?></option>
                        <?php } ?>
                    <?php endforeach; ?>
                    </select>
                    <label for="status_produk">Status produk</label>
                    <select name="status_produk" id="status_produk">
                        <?php if($items[0]['status_produk']==="Ready") { ?>
                            <option value="Ready" selected>Ready</option>
                            <option value="Sold out">Sold out</option>
                        <?php } else if ($items[0]['status_produk']==="Sold out") {?>
                            <option value="Ready" >Ready</option>
                            <option value="Sold out" selected>Sold out</option>
                        <?php } ?>
                    </select>

                    <label for="berat_produk">Berat produk</label>
                    <span><input id="berat_produk" name="berat_produk" type="number" value="<?= $items[0]['berat_produk'] ?>"> Gram</span>

                    <label for="gambar_produk">Gambar produk</label>
                    <img src="../img/<?= $items[0]['gambar_produk'] ?>" alt="<?php $items[0]['nama_produk'] ?>" width="150" style="margin-bottom: 10px">
                    <input style="all: initial; 
                    font-family: 'Poppins', sans-serif; 
                    color: #121920; 
                    width:240px;" id="gambar_produk" name="gambar_produk" type="file">

                    <label for="keterangan_produk">Keterangan produk</label>
                    <textarea name="keterangan_produk" id="keterangan_produk" style="resize: none" placeholder="Masukan keterangan dari produk"><?= $items[0]['keterangan_produk'] ?></textarea>

                    <button onclick="return confirm('Apakah anda ingin mengupdate produk?'); " name="btn-update" type="submit">UPDATE</button>
                </form>
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