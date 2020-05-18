<?php
require 'connection.php';
$id_transaksi = $_POST['id'];
$metode = $_POST['metode'];
$btn = $_POST['btn-confirm'];

if(isset($_POST['btn-confirm'])){
    global $conn;
    global $id_transaksi;
    global $metode;

    //MASUKAN KE TABLE PEMBAYARAN
    $query ="INSERT INTO pembayaran(id_transaksi,metode_pembayaran) VALUES('$id_transaksi','$metode')";
 
    $result = mysqli_query($conn,$query);
    if(mysqli_affected_rows($conn)>0){
        echo "
        <script> 
        alert('Barang berhasil dipesan');
        document.location.href = '../pemesanan.php';
        </script>";
        exit;
    } else {
        echo mysqli_error($conn);
    }
}