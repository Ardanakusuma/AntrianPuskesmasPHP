<?php

    session_start();

    if ( !isset($_SESSION['login'])) {
        header("Location: ../login.php");
        exit;
    } 

    $conn = mysqli_connect("localhost", "root", "", "antri");

    if (isset($_GET['id'])) {
        $idDokter = $_GET['id'];

        // Mengambil data pasien berdasarkan ID_Pasien
        $query = "SELECT * FROM Dokter WHERE ID_Dokter = $idDokter";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        $nama_d = $row['Nama_D'];
        $spesialis = $row['Spesialis'];
        $jadwal = $row['Jadwal'];
        
    }

    // Memperbarui data pasien
    if (isset($_POST['submit'])) {
        $namaBaru = $_POST['nama_d'];
        $spesialisBaru = $_POST['spesialis'];
        $jadwalBaru = $_POST['jadwal'];

        $queryUbah = "UPDATE Dokter SET Nama_D = '$namaBaru', Spesialis = '$spesialisBaru', Jadwal = '$jadwalBaru' WHERE ID_Dokter = $idDokter";

        var_dump($queryUbah);

        mysqli_query($conn, $queryUbah);

        if(mysqli_query($conn, $queryUbah)) {
            echo "
                <script>
                    alert('Data dokter berhasil diperbarui');
                    document.location.href = 'tampildokter.php';
                </script>
            ";

        } else {
            echo "
                <script>
                    alert('Data dokter gagal diperbarui');
                    document.location.href = 'tampildokter.php';
                </script>
            ";
        }

    }

?>


<!DOCTYPE html>
<html>
<head>
	<title>Ubah Data Dokter</title>
	<link rel="stylesheet" type="text/css" href="../style2.css">
</head>
<body>
	<div class="form-container-dokter">
        <h1>Edit Data Dokter</h1>
        <form action="" method="post" enctype="multipart/form-data">
			<div class="form-group-dokter">
				<label for="nama_d">Nama</label>
				<input type="text" name="nama_d" value="<?= $nama_d; ?>" required>
			</div>
			<div class="form-group-dokter">
				<label for="spesialis">Spesialis</label>
				<select name="spesialis" id="spesialis" value="<?= $spesialis; ?>" required>
					<option value="">-Ubah Spesialis-</option>
					<option value="Jantung">Jantung</option>
					<option value="Paru-paru">Paru-paru</option>
					<option value="Bedah">Bedah</option>
					<option value="Mata">Mata</option>
					<option value="Kulit">Kulit</option>
					<option value="Gigi">Gigi</option>
					<option value="THT">THT</option>
					<option value="Anak">Anak</option>
					<option value="Syaraf">Syaraf</option>
					<option value="Kandungan">Kandungan</option>
				</select>
			</div>
			<div class="form-group-dokter">
				<label for="no_hp">Jadwal </label>
				<select name="jadwal"  value="<?= $jadwal; ?>" required>
					<option value="">-Ubah Jadwal-</option>
                    <option value="Senin (09.00-17.00) ">Senin (09.00-17.00)</option>
                    <option value="Selasa (10.00-18.00)">Selasa (10.00-18.00)</option>
                    <option value="Rabu (12.00-20.00)  ">Rabu (12.00-20.00)</option>
                    <option value="Kamis (08.00-16.00) ">Kamis (08.00-16.00)</option>
                    <option value="Jumat (09.00-17.00) ">Jumat (09.00-17.00)</option>
                    <option value="Sabtu (10.00-18.00) ">Sabtu (10.00-18.00)</option>
                    <option value="Minggu (11.00-19.00 )">Minggu (11.00-19.00)</option>
				</select>
			</div>
			<div class="form-actions-dokter">
				<a href="tampildokter.php" class="btn-back-dokter">Kembali</a>
				<button type="reset" class="reset-button-dokter">Reset</button>
				<button type="submit" name="submit">Simpan Data</button>
			</div>
		</form>
	</div>
</body>
</html>
