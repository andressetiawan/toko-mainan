<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['login']){
    header('Location: index.php');
}

require './include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];
$categories = query("SELECT * FROM kat_produk");
$payments = query("SELECT * FROM kat_pembayaran");

$query = "SELECT bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC";

//TAMPILKAN PEMBAYARAN PUNYA USER
$details = query($query);

//KATEGORI PEMESANAN
if(isset($_GET['q'])){
    $id = $_SESSION['id_user'];
    $kategori = $_GET['q'];
    $details = query("SELECT DISTINCT * FROM user NATURAL JOIN produk NATURAL JOIN transaksi NATURAL JOIN kat_pembayaran NATURAL JOIN kat_pengiriman NATURAL JOIN pembayaran NATURAL JOIN pengiriman WHERE transaksi.id_produk=produk.id_produk AND id_kat_produk='$kategori' AND user.id_user = '$id'");
}

//FILTER DATA
if(isset($_POST['btn-search'])){
    //SEMUA METODE PEMBAYARAN TRANSFER
    if($_POST['filter'] == 1){
    $details = query("SELECT bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND pembayaran.id_kat_pembayaran = '1' AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC");
    } else if($_POST['filter'] == 2){
    //SEMUA METODE PEMBAYARAN OVO
    $details = query("SELECT bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND pembayaran.id_kat_pembayaran = '2' AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC");
    } else if($_POST['filter'] == "waiting"){
    //SEMUA STATUS PEMBAYARAN WAITING
    $details = query("SELECT bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND status_pembayaran = 'waiting' AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC");
    } else if($_POST['filter'] == "Checking"){
    //SEMUA STATUS PEMBAYARAN CHECKING
    $details = query("SELECT bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND status_pembayaran = 'Checking' AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC");
    } else if($_POST['filter'] == "Resubmit"){
    //SEMUA STATUS PEMBAYARAN RESUBMIT
    $details = query("SELECT bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND status_pembayaran = 'Resubmit' AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC");
    } else if($_POST['filter'] == "Approved"){
    //SEMUA STATUS PEMBAYARAN APPROVED
    $details = query("SELECT bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND status_pembayaran = 'Approved' AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC");
    } else if($_POST['filter'] == "Packing"){
    //SEMUA STATUS PENGIRIMAN PACKING
    $details = query("SELECT bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND status_pengiriman = 'Packing' AND status_pembayaran = 'Approved' AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC");
    } else if($_POST['filter'] == "Sending"){
    //SEMUA STATUS PEMBAYARAN WAITING
    $details = query("SELECT bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND status_pengiriman = 'Sending' AND status_pembayaran = 'Approved' AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC");
    } else if($_POST['filter'] == "Arrived"){
    //SEMUA STATUS PEMBAYARAN WAITING
    $details = query("SELECT bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND status_pengiriman = 'Arrived' AND status_pembayaran = 'Approved' AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/pemesanan.css">
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
            <form action="pemesanan.php" method="post">
                <select name="filter" id="filter">
                    <optgroup label="JENIS PEMBAYARAN" >
                        <?php foreach($payments as $payment) : ?>
                        <option value="<?= $payment['id_kat_pembayaran'] ?>"><?= $payment['jenis_pembayaran'] ?></option>
                        <?php endforeach;?>
                    </optgroup>

                    <optgroup label="STATUS PEMBAYARAN" >
                        <option value="waiting">WAITING</option>
                        <option value="Checking">CHECKING</option>
                        <option value="Resubmit">RESUBMIT</option>
                        <option value="Approved">APPROVED</option>
                    </optgroup>

                    <optgroup label="STATUS PENGIRIMAN" >
                        <option value="Packing">PACKING</option>
                        <option value="Sending">SENDING</option>
                        <option value="Arrived">ARRIVED</option>
                    </optgroup>
                </select>
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
                <a href="pemesanan.php?q=<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk'] ?></a>
            <?php endforeach;?>
        </section>  
    </aside>
        <section id="main">
            <?php foreach($details as $detail) : ?>
            <div id="product">
                <div id="keterangan">
                    <h1>#<?= $detail['id_transaksi'] ?> </h1>
                    <p><em><?= $detail['nama_produk'] ?></em></p>
                    <p>Total pembayaran : <b><?= rupiah($detail['jumlah']*$detail['harga_produk']) ?></b></p>
                    <p>Jenis pembayaran : <b style="text-transform: capitalize";><?= $detail['jenis_pembayaran'] ?></b></p>
                    <p>Jenis pengiriman : <b style="text-transform: capitalize";><?= $detail['jenis_pengiriman'] ?></b></p>
                    <p>Status pembayaran : <b style="text-transform: capitalize";><?= $detail['status_pembayaran'] ?></b></p>

                </div>

                <div id="tool">

                    <form action="pembayaran.php" method="post">
                        <!-- KALAU BUKTI PEMBAYARAN ADA TIDAK USAH TAMPILKAN KONFIRMASI PEMBAYARAN -->
                        <?php if(is_null($detail['bukti_pembayaran']) && $detail['id_kat_pembayaran']=="1") : ?>
                            <input type="hidden" name="id_transaksi" value="<?= $detail['id_transaksi'] ?>">
                            <p>Transfer</p>
                            <p><b>BCA 0000-88-0000</b></p>
                            <p>a.n Darryl Nathanael</p>
                            <button name="btn-pembayaran" type="submit">KONFIRMASI PEMBAYARAN</button>
                        <?php endif; ?>

                        <?php if(is_null($detail['bukti_pembayaran']) && $detail['id_kat_pembayaran']=="2")  : ?>
                            <input type="hidden" name="id_transaksi" value="<?= $detail['id_transaksi'] ?>">
                            <p>OVO Barcode</p>
                            <img src="./img/OVO.jpg" alt="Ovo Barcode" width="100">
                            <p>a.n Darryl Nathanael</p>
                            <button name="btn-pembayaran" type="submit">KONFIRMASI PEMBAYARAN</button>
                        <?php endif; ?>

                        <?php if(!is_null($detail['bukti_pembayaran']) &&  $detail['id_kat_pembayaran']=="1" && $detail['status_pembayaran'] == "Resubmit") :?>
                            <input type="hidden" name="id_transaksi" value="<?= $detail['id_transaksi'] ?>">
                            <p>Transfer</p>
                            <p><b>BCA 0000-88-0000</b></p>
                            <p>a.n Darryl Nathanael</p>
                            <button name="btn-pembayaran" type="submit">KONFIRMASI PEMBAYARAN</button>
                        <?php endif; ?>

                        <?php if(!is_null($detail['bukti_pembayaran']) &&  $detail['id_kat_pembayaran']=="2" && $detail['status_pembayaran'] == "Resubmit") :?>
                            <input type="hidden" name="id_transaksi" value="<?= $detail['id_transaksi'] ?>">
                            <p>OVO Barcode</p>
                            <img src="./img/OVO.jpg" alt="Ovo Barcode" width="100">
                            <p>a.n Darryl Nathanael</p>
                            <button name="btn-pembayaran" type="submit">KONFIRMASI PEMBAYARAN</button>
                        <?php endif; ?>

                        <?php if(!is_null($detail['bukti_pembayaran']) && $detail['status_pengiriman']!=="Arrived") :?>
                            <h3 style="text-transform: uppercase"><?= $detail['status_pembayaran'] ?></h3>
                        <?php endif; ?>

                        <?php if(!is_null($detail['bukti_pembayaran']) && $detail['status_pengiriman']=="Arrived") :?>
                            <h3 style="text-transform: uppercase"><?= $detail['status_pengiriman'] ?></h3>
                        <?php endif; ?>
                    </form>

                    <?php if(!is_null($detail['bukti_pembayaran']) && $detail['status_pembayaran'] === "Approved" && $detail['status_pengiriman'] === "Packing") :?>
                        <div id="pengiriman">
                            <div id="status-box">
                            <p>Lastest Update</p>
                            <p style="font-size: large"> <b><?= $detail['status_pengiriman'] ?></b></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(!is_null($detail['bukti_pembayaran']) && $detail['status_pembayaran'] === "Approved" && $detail['status_pengiriman'] === "Sending") :?>
                        <div id="pengiriman">
                            <div id="status-box">
                            <p>Lastest Update</p>
                            <p style="font-size: large"> <b><?= $detail['status_pengiriman'] ?></b></p>
                            <a  href="include/confirm.php?c=<?= $detail['id_pengiriman'] ?>">Konfirmasi pengiriman</a>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <?php endforeach;?>
        </section>
    </main>

    <footer></footer>
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