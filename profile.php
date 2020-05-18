<?php
session_start();
require 'include/functions.php';
$id = $_SESSION['id_user'];
$userData = query("SELECT * FROM user WHERE id_user = '$id'");
$addresses = query("SELECT * FROM alamat LIMIT 5");
$passwordHash = $userData [0]['password'];
$username = $_SESSION['nama'];

//UPDATE | DATA USER
if(isset($_POST['btn-save'])){

    //KALAU MAU GANTI PASSWORD
    if(password_verify($_POST['old-password'],$passwordHash) && $_POST['new-password']!==''){
        if($_POST['alamat']==''){
            update($_POST);
            echo '<script> 
            alert("Data berhasil disimpan");
            document.location.href = "user.php";
            </script>';
            exit;
        } else {
            update2($_POST);
            echo '<script> 
            alert("Data berhasil disimpan");
            document.location.href = "user.php";
            </script>';
            exit;
        }
        
    } 
    //KALAU GA MAU GANTI PASSWORD
    if($_POST['old-password']=='' && $_POST['new-password']==''){
        if($_POST['alamat']==''){
            updateBiasa2($_POST);
            echo '<script> 
            alert("Data berhasil disimpan");
            document.location.href = "user.php";
            </script>';
            exit;
        } else {
            updateBiasa($_POST);
            echo '<script> 
            alert("Data berhasil disimpan");
            document.location.href = "user.php";
            </script>';
            exit;
        }
    }
}

$categories = query("SELECT * FROM kat_produk");
$id_alamat_user = $userData [0]['id_alamat'];
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

        <!-- Kanan main area -->
        <section id="main">
            <div id="kategori-signup">
            <?php foreach($categories as $categorie) :?>
                <a href="user.php?q=<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk'] ?></a>
            <?php endforeach;?>
            </div>

            <div id="registrasi">
                <form action="" method="post">
                    <input type="hidden" name="id_user" value="<?= $userData [0]['id_user'];?>">

                    <label for="user">Username</label>
                    <input type="text" name="user" id="user" value="<?= $userData [0]['username'];?>">

                    <label for="old-password">Old Password</label>
                    <input type="password" name="old-password" id="old-password">

                    <label for="new-password">New Password</label>
                    <input type="password" name="new-password" id="new-password">

                    <label for="nama_depan">Nama depan </label>
                    <input type="text" name="nama-depan" id="nama-depan" value="<?= $userData [0]['nama_depan'];?>">

                    <label for="nama_belakang">Nama belakang</label> 
                    <input type="text" name="nama-belakang" id="nama-belakang" value="<?= $userData [0]['nama_belakang'];?>">

                    <label for="nama_belakang">Jenis kelamin</label> 
                    <select name="jk" id="jk">
                        <?php if($userData [0]['jk_user'] == "Laki-laki" ) {?>
                        <option value="Laki-laki" selected>Laki-Laki</option>
                        <option value="Perempuan" >Perempuan</option>
                        <?php } else { ?>
                        <option value="Laki-laki" >Laki-Laki</option>
                        <option value="Perempuan" selected>Perempuan</option>
                        <?php } ?>
                    </select>

                    <label for="tgl_lahir">Tanggal lahir</label> 
                    <input type="date" name="tgl-lahir" id="tgl-lahir" value="<?= $userData [0]['tgl_lhr_user']  ?>">

                    <label for="email">Email</label> 
                    <input type="email" name="email" id="email" value="<?= $userData [0]['email'] ?>">

                    <label for="tgl_lahir">Nomor Handphone</label> 
                    <input type="number" name="no-tlp" id="no-tlp" value="<?= $userData [0]['no_hp_user'] ?>">

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
                            <?php 
                            foreach($addresses as $address) : ?>
                            <tr>
                                <td><?=$address['kelurahan']?></td>
                                <td><?=$address['kabupaten']?></td>
                                <td><?=$address['provinsi']?></td>
                                <td><input type="radio" name="alamat" id="alamat" value="<?=$address['id_alamat']?>"></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <button id='btn-save' name="btn-save" type="submit">Save</button>
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