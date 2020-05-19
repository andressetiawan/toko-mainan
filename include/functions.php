<?php
require 'connection.php';
//SELECT AND FETCH DATA
function query ($sql){
    global $conn;
    $result=mysqli_query($conn,$sql);
    $output=[];
    while($data = mysqli_fetch_assoc($result)){
        $output[]=$data;
    }
    return $output;
}

// UPDATE DATA
function update($data){
    global $conn;
    $username = htmlspecialchars(strtolower(stripslashes($data['user'])));
    $password = mysqli_real_escape_string($conn,$data['new-password']);
    $password= password_hash($password,PASSWORD_DEFAULT);
    $email = htmlspecialchars($data['email']);
    $nama_depan= htmlspecialchars($data['nama-depan']);
    $nama_belakang= htmlspecialchars($data['nama-belakang']);
    $jenis_kelamin= $data['jk'];
    $no_tlp= htmlspecialchars($data['no-tlp']);
    $tanggal_lhr = $data['tgl-lahir'];
    $id_user = $data['id_user'];
    $query = "UPDATE user SET username = '$username', password = '$password' , email = '$email', nama_depan = '$nama_depan', nama_belakang = '$nama_belakang', jk_user = '$jenis_kelamin', no_hp_user = '$no_tlp' , tgl_lhr_user = STR_TO_DATE('$tanggal_lhr','%Y-%m-%d') WHERE id_user = '$id_user'";
    $result = mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}
function update2($data){
    global $conn;
    $alamat = $data['alamat'];
    $username = htmlspecialchars(strtolower(stripslashes($data['user'])));
    $password = mysqli_real_escape_string($conn,$data['new-password']);
    $password= password_hash($password,PASSWORD_DEFAULT);
    $email = htmlspecialchars($data['email']);
    $nama_depan= htmlspecialchars($data['nama-depan']);
    $nama_belakang= htmlspecialchars($data['nama-belakang']);
    $jenis_kelamin= $data['jk'];
    $no_tlp= htmlspecialchars($data['no-tlp']);
    $tanggal_lhr = $data['tgl-lahir'];
    $id_user = $data['id_user'];
    $query = "UPDATE user SET username = '$username', password = '$password' , email = '$email', nama_depan = '$nama_depan', nama_belakang = '$nama_belakang', jk_user = '$jenis_kelamin', no_hp_user = '$no_tlp' , tgl_lhr_user = STR_TO_DATE('$tanggal_lhr','%Y-%m-%d'), id_alamat = '$alamat' WHERE id_user = '$id_user'";
    $result = mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}
function updateBiasa($data){
    global $conn;
    $alamat1 = $data['alamat'];
    $username1 = htmlspecialchars(strtolower(stripslashes($data['user'])));
    $email1 = htmlspecialchars($data['email']);
    $nama_depan1= htmlspecialchars($data['nama-depan']);
    $nama_belakang1= htmlspecialchars($data['nama-belakang']);
    $jenis_kelamin1= $data['jk'];
    $no_tlp1= htmlspecialchars($data['no-tlp']);
    $tanggal_lhr1 = $data['tgl-lahir'];
    $id_user1 = $data['id_user'];
    $query1 = "UPDATE user SET username = '$username1', email = '$email1', nama_depan = '$nama_depan1', nama_belakang = '$nama_belakang1', jk_user = '$jenis_kelamin1', no_hp_user = '$no_tlp1', tgl_lhr_user = STR_TO_DATE('$tanggal_lhr1','%Y-%m-%d'), id_alamat = '$alamat1' WHERE id_user = '$id_user1'";
    $result = mysqli_query($conn, $query1);
    return mysqli_affected_rows($conn);
}
function updateBiasa2($data){
    global $conn;
    $username1 = htmlspecialchars(strtolower(stripslashes($data['user'])));
    $email1 = htmlspecialchars($data['email']);
    $nama_depan1= htmlspecialchars($data['nama-depan']);
    $nama_belakang1= htmlspecialchars($data['nama-belakang']);
    $jenis_kelamin1= $data['jk'];
    $no_tlp1= htmlspecialchars($data['no-tlp']);
    $tanggal_lhr1 = $data['tgl-lahir'];
    $id_user1 = $data['id_user'];
    $query1 = "UPDATE user SET username = '$username1', email = '$email1', nama_depan = '$nama_depan1', nama_belakang = '$nama_belakang1', jk_user = '$jenis_kelamin1', no_hp_user = '$no_tlp1', tgl_lhr_user = STR_TO_DATE('$tanggal_lhr1','%Y-%m-%d') WHERE id_user = '$id_user1'";
    $result = mysqli_query($conn, $query1);
    return mysqli_affected_rows($conn);
}

//HAPUS DATA
function hapus($data){
    global $conn;
    $id_transaksi=htmlspecialchars($data['id_transaksi']);
    $query="DELETE FROM transaksi WHERE id_transaksi = $id_transaksi";
    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}

//UPDATE AUTO UPDATE STOK
function updateStok($data){
global $conn;
$stok = $data['stok'];
$jumlah = $data['jumlah'];
$stokBaru = $stok-$jumlah;
if($stokBaru <= 0){
    return false;
} else {
    $query = "UPDATE produk SET stok = '$stokBaru'";
    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}
}

//INPUTAN DATA KE PRODUK
function input($data){
global $conn;
$kategori = $data['kategori_produk'];
$nama_produk = $data['nama_produk'];
$stok = $data['stok'];
$harga_produk = $data['harga_produk'];
$berat_produk = $data['berat_produk'];
$gambar_produk = uploadGambar();
if(!$gambar_produk){
    return false;
}
$keterangan_produk = $data['keterangan_produk'];
$query = "INSERT INTO produk VALUES('','$kategori','$nama_produk','$stok','$harga_produk','$berat_produk','$gambar_produk','Ready','$keterangan_produk')";
$result = mysqli_query($conn,$query);
return mysqli_affected_rows($conn);
}
function uploadGambar(){
    $namaGambar = $_FILES['gambar_produk']['name'];
    $ukuranGambar = $_FILES['gambar_produk']['size'];
    $error = $_FILES['gambar_produk']['error'];
    $pathGambar = $_FILES['gambar_produk']['tmp_name'];

    //FILE SUDAH DIMASUKAN ATAU BELUM
    if($error === 4){
        echo '<script>
        alert("Masukan gambar terlebih dahulu");
        </script>';
        return false;
    }

    //CEK EKTERNSI FILE
    $formatGambar=['jpg','jpeg','png'];
    $formatBukti=explode('.',$namaGambar);
    $formatBukti = strtolower(end($formatBukti));
    if(!in_array($formatBukti,$formatGambar)){
        echo '<script>
        alert("Yang anda upload bukan gambar");
        </script>';
        return false;
    }

    //KALAU FILE BESAR BANGET
    $ukuranFileByte = 30000000; //30MB
    if($ukuranGambar > $ukuranFileByte){
        echo '<script>
        alert("Ukuran file terlalu besar");
        </script>';
        return false;
    }
    move_uploaded_file($pathGambar,'../img/'.$namaGambar);
    return $namaGambar;
}

//UPDATE PEMBAYARAN
function updatePembayaran($data){
global $conn;
$id_transaksi = $data['id'];
$tanggal_pembayaran = $data['tgl_pembayaran'];
$bukti = upload();
if(!$bukti){
    return false;
}
$query = "UPDATE pembayaran SET tgl_pembayaran = '$tanggal_pembayaran', bukti_pembayaran = '$bukti', status_pembayaran = 'Checking' WHERE id_transaksi = '$id_transaksi'";
mysqli_query($conn,$query);
return mysqli_affected_rows($conn);
}

function upload(){
    $namaGambar = $_FILES['bukti']['name'];
    $ukuranGambar = $_FILES['bukti']['size'];
    $error = $_FILES['bukti']['error'];
    $pathGambar = $_FILES['bukti']['tmp_name'];

    //FILE SUDAH DIMASUKAN ATAU BELUM
    if($error === 4){
        echo '<script>
        alert("Masukan bukti pembayaran");
        </script>';
        return false;
    }

    //CEK EKTERNSI FILE
    $formatGambar=['jpg','jpeg','png'];
    $formatBukti=explode('.',$namaGambar);
    $formatBukti = strtolower(end($formatBukti));
    if(!in_array($formatBukti,$formatGambar)){
        echo '<script>
        alert("Yang anda upload bukan gambar");
        </script>';
        return false;
    }

    //KALAU FILE BESAR BANGET
    $ukuranFileByte = 1000000; //1MB
    if($ukuranGambar > $ukuranFileByte){
        echo '<script>
        alert("Ukuran file terlalu besar");
        </script>';
        return false;
    }
    move_uploaded_file($pathGambar,'pembayaran/'.$namaGambar);
    return $namaGambar;
}

//INPUTAN DATA
function simpan($data){
    global $conn;
    $id_user=htmlspecialchars($data['id_user']);
    $id_produk= $data['id_produk'];
    $jumlah= $data['jumlah'];
    $query="INSERT INTO transaksi(id_user,id_produk,jumlah) VALUES('$id_user','$id_produk','$jumlah')";
    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}

//FORMAT RUPIAH
function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}

//SIGN UP | REGISTRASI
function signup($data){
    global $conn;
    $alamat = $data['alamat'];
    $username = htmlspecialchars(strtolower(stripslashes($data['user'])));
    $password = mysqli_real_escape_string($conn,$data['password']);
    $password= password_hash($password,PASSWORD_DEFAULT);
    $email = htmlspecialchars($data['email']);
    $nama_depan= htmlspecialchars($data['nama-depan']);
    $nama_belakang= htmlspecialchars($data['nama-belakang']);
    $jenis_kelamin= $data['jk'];
    $no_tlp= htmlspecialchars($data['no-tlp']);
    $tanggal_lhr = $data['tgl-lahir'];

    $cekQuery = "SELECT * FROM user WHERE username='$username'";
    $result = mysqli_query($conn,$cekQuery);
    if($username=='' && $password=='' && $nama_depan=='' || $nama_belakang=='' 
    || $no_tlp=='' || $tanggal_lhr=='' || $jenis_kelamin=='' || $email=='' || $alamat==''){
        echo "<script>
        alert('Data belum lengkap');
        </script>";
        return false;
    }

    if(mysqli_affected_rows($conn) == 1 ){
        echo "<script>
        alert('Username sudah dipakai');
        document.location.href = 'signup.php';
        </script>";
        return false;
    } else {
        mysqli_query($conn,"INSERT INTO user(id_alamat,username,password,email,nama_depan,nama_belakang,tgl_lhr_user,jk_user,no_hp_user) VALUES ('$alamat','$username','$password','$email','$nama_depan','$nama_belakang',STR_TO_DATE('$tanggal_lhr','%Y-%m-%d'),'$jenis_kelamin','$no_tlp')");
        return mysqli_affected_rows($conn);
    }
}

?>