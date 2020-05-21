<?php
//KONEKSI DATABASE
$username = "root";
$password = '';
$host = 'localhost';
$database = 'toko_mainan';
$conn = mysqli_connect($host,$username,$password,$database);
if(!$conn){
    die("Connection error ".mysqli_connect_error());
}
?>