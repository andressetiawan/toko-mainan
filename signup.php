<?php
//APAKAH SUDAH LOGIN?
if(isset($_SESSION['login'])){
    header('Location: user.php');
} 
else if (isset($_SESSION['masuk'])) {
    header('Location: admin/admin.php');
}
require 'include/functions.php';
$categories = query("SELECT * FROM kat_produk");
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
    <link rel="stylesheet" href="./css/signup.css">
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

        <!-- Kanan main area -->
        <section id="main">
            <div id="kategori-signup">
            <?php foreach($categories as $categorie) :?>
                <a href="index.php?q=<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk'] ?></a>
            <?php endforeach;?>
            </div>

            <div id="registrasi">
                <form action="" method="post">
                    <label for="user">Username</label>
                    <input type="text" name="user" id="user">

                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">

                    <label for="nama_depan">Nama depan </label>
                    <input type="text" name="nama-depan" id="nama-depan">

                    <label for="nama_belakang">Nama belakang</label> 
                    <input type="text" name="nama-belakang" id="nama-belakang">

                    <label for="nama_belakang">Jenis kelamin</label> 
                    <select name="jk" id="jk">
                        <option value="Laki-laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>

                    <label for="tgl_lahir">Tanggal lahir</label> 
                    <input type="date" name="tgl-lahir" id="tgl-lahir">

                    <label for="email">Email</label> 
                    <input type="email" name="email" id="email">

                    <label for="tgl_lahir">Nomor Handphone</label> 
                    <input type="number" name="no-tlp" id="no-tlp">

                    <label for="tgl_lahir">Cari alamat</label> 
                    <input type="text" name="alamat" id="alamatInput" placeholder="Cari alamat anda">
                     <div id="tabel-alamat">
                        <table style="text-align: center;" cellpadding='3' >
                            <tr>
                                <th>Kelurahan</th>
                                <th>Kabupaten</th>
                                <th>Provinsi</th>
                                <th>Pilih</th>
                            </tr>
                            <?php foreach($addresses as $address) : ?>
                            <tr>
                                <td><?=$address['kelurahan']?></td>
                                <td><?=$address['kabupaten']?></td>
                                <td><?=$address['provinsi']?></td>
                                <td><input type="radio" name="alamat" id="alamat" value="<?=$address['id_alamat']?>"></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <button id='btn-daftar' name="btn-daftar" type="submit">Daftar</button>
                </form>
            </div>
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
    </script>
</body>
</html>