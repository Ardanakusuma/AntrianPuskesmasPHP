<?php 

	session_start();

	if ( !isset($_SESSION['login'])) {
		header("Location: ../login.php");
		exit;
	} 
	
	$conn = mysqli_connect("localhost", "root", "", "antri");

	if(isset($_POST['submit'])){


        $nik = htmlspecialchars($_POST['nik']);
		$nama_p = htmlspecialchars($_POST['nama_p']);
		$alamat = htmlspecialchars($_POST['alamat']);
        $domisili = htmlspecialchars($_POST['domisili']);
		$tgl_lahir = $_POST['tgl_lahir'];
		$no_hp = htmlspecialchars($_POST['no_hp']);
		$jenis_kelamin = $_POST['jenis_kelamin'];
        
        //upload ktp/kk
        $namaFile = $_FILES['ktp_kk']['name'];
        $directory = $_FILES['ktp_kk']['tmp_name'];

        //cek gambar
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.',$namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));

        if(!in_array($ekstensiGambar, $ekstensiGambarValid)){
            echo "
                <script>
                    alert('File yang diupload harus jpg/jpeg/png');
                    document.location.href = 'tampilpasien.php';
                </script>
            ";
            exit;
        }

        //ganti nama file

        $namaFileBaru = uniqid();
        $namaFileBaru .= '.';
        $namaFileBaru .= $ekstensiGambar;

        move_uploaded_file($directory, 'img/' . $namaFileBaru);


        if($idPasien == ""){
            $idPasien = 1;
        }

		$query1 = "INSERT INTO Pasien (Nama_P, Alamat, Domisili, Tgl_lahir, No_HP, Jenis_Kelamin, KTP_KK) VALUES ('$nama_p', '$alamat', '$domisili', '$tgl_lahir', '$no_hp', '$jenis_kelamin', '$namaFileBaru')";

		if (mysqli_query($conn, $query1)) {
            echo "
            	<script>
            		alert('Data pasien berhasil ditambahkan');
            		document.location.href = 'tampilpasien.php';
            	</script>
            ";

        } else {
            echo "
            	<script>
            		alert('Data pasien gagal ditambahkan');
            		document.location.href = 'tampilpasien.php';
            	</script>
            ";
        }
	}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Pasien</title>
    <link rel="stylesheet" href="../style2.css">
</head>
<body>
    <div class="form-container-pasien">
        <h1>Tambah Data Pasien</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-column-pasien">
                <div class="form-group-pasien">
                    <label for="nik">NIK (No Induk Kependudukan)</label>
                    <input type="text" name="nik" id="nik" required>
                </div>

                <div class="form-group-pasien">
                    <label for="nama_p">Nama</label>
                    <input type="text" name="nama_p" id="nama_p" required>
                </div>

                <div class="form-group-pasien">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" id="alamat" required>
                </div>
                <div class="form-group-pasien">
                    <label for="no_hp">No. HP</label>
                    <input type="text" name="no_hp" id="no_hp" required>
                </div>
                <a href="tampilpasien.php" class="btn-back-pasien">Kembali</a>


            </div>
            <div class="form-column-pasien">
                <div class="form-group-pasien">
                    <label for="tgl_lahir">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="tgl_lahir" required>
                </div>
                <div class="form-group-pasien">
                    <label for="Domisili">Domisili</label>
                    <select type="text" name="Pilih Domisili" id="Domisili" required>
                        <option value="">-Pilih Domisili-</option>
                        <option>Banjarmasin</option>
                        <option>Banyumas</option>
                        <option>Banjarmasin</option>
                        <option>Purwokerto</option>
                        <option>Banjarnegara</option>
                    </select>
                </div>
                <div class="form-group-pasien">
                <td><label for="jenis_kelamin">Jenis Kelamin</label></td>
                    <select type="text" name="jenis_kelamin" required>
                        <option value="">-Pilih jenis kelamin-</option>
                        <option value="L">Laki-laki👨🏻</option>
                        <option value="P">Perempuan👩🏻</option>
                    </select>
                    
                </div>

                <div class="form-group-pasien">
                    <label for="ktp_kk">Foto KTP / KK:</label>
                    <label for="images" class="drop-container">
                    </td>
                    <input type="file" name="ktp_kk" id="ktp_kk" required></td>
                    </label>
                </div>
                <div class="form-actions-pasien">
                    <button type="reset" class="reset-button-pasien">Reset</button>
                    <button type="submit" name="submit">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

