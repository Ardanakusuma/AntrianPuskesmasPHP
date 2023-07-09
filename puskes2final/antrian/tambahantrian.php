<?php 

	session_start();

	/*if ( !isset($_SESSION['login'])) {
		header("Location: ../login.php");
		exit;
	} */
	
	$conn = mysqli_connect("localhost", "root", "", "antri");

	// Fitur auto complete
	$queryc = "SELECT ID_Pasien, Nama_P FROM Pasien";
	$resultc = mysqli_query($conn, $queryc);
	$queryc2 = "SELECT ID_Dokter, Nama_D, Spesialis FROM Dokter";
	$resultc2 = mysqli_query($conn, $queryc2);

	$pilihanPasien = array();
	while ($row = mysqli_fetch_assoc($resultc)) {
	    $pilihanPasien[] = $row['ID_Pasien'] . ' - ' . $row['Nama_P'];
	}

	$pilihanDokter = array();
	while ($row = mysqli_fetch_assoc($resultc2)) {
	    $pilihanDokter[] = $row['ID_Dokter'] . ' - ' . $row['Nama_D'] . ' (Spesialis: ' . $row['Spesialis'] . ')';
	}

	// Inisialisasi input teks
	$idPasien = '';
	$idDokter = '';

	// Proses ketika form dikirimkan
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	    $idPasien = $_POST['idPasien'];
	    $idDokter = $_POST['idDokter'];
	}


	if(isset($_POST['submit'])){
		$Antrian = $_GET['ruangan'];
		$idAntrian = $_GET['id'];
		$idPasien = $_POST['idPasien'];
		$idResepsionis = $_SESSION['login'];	
		$idDokter = $_POST['idDokter'];
		$tglAntrian = date('Y-m-d');
		$status = "Menunggu";

		if($idAntrian == ""){
			$idAntrian = '1';
			$status = "Diperiksa";
			if($Antrian == "Antrian"){
				$_SESSION['start'] = true;
				$page = '1';
			}elseif($Antrian == "Antrian2"){
				$_SESSION['start2'] = true;
				$page = '2';
			}else{
				$_SESSION['start3'] = true;
				$page = '3';
			}
		}

		if($Antrian == "Antrian"){
			$page = '1';
		}elseif($Antrian == "Antrian2"){
			$page = '2';
		}else{
			$page = '3';
		}

		$idPasien = explode(" ", $idPasien)[0];

		$idDokter = explode(" ", $idDokter)[0];


		$querycek1 = "SELECT ID_Pasien FROM Antrian";
		$querycek11 = "SELECT ID_Pasien FROM Antrian2";
		$querycek111 = "SELECT ID_Pasien FROM Antrian3";
		$check1 = mysqli_query($conn, $querycek1);
		$check11 = mysqli_query($conn, $querycek11);
		$check111 = mysqli_query($conn, $querycek111);

		while ($row = mysqli_fetch_assoc($check1)){
			if($idPasien == $row["ID_Pasien"]){
				echo "
            	<script>
            		alert('Pasien sudah mendapat antrian di ruangan 1');
            		document.location.href = '../index1.php?page=$page';
            	</script>
            	";
            	exit;
			}
		}

		while ($row = mysqli_fetch_assoc($check11)){
			if($idPasien == $row["ID_Pasien"]){
				echo "
            	<script>
            		alert('Pasien sudah mendapat antrian di ruangan 2');
            		document.location.href = '../index1.php?page=$page';
            	</script>
            	";
            	exit;
			}
		}

		while ($row = mysqli_fetch_assoc($check111)){
			if($idPasien == $row["ID_Pasien"]){
				echo "
            	<script>
            		alert('Pasien sudah mendapat antrian di ruangan 3');
            		document.location.href = '../index1.php?page=$page';
            	</script>
            	";
            	exit;
			}
		}

		$querycek2 = "SELECT  COUNT(*) AS total FROM Pasien";
		$check2 = mysqli_query($conn, $querycek2);
		$row2 = mysqli_fetch_assoc($check2);
		$totalPasien = $row2["total"];

		if($totalPasien == 0){
			echo "
            	<script>
            		alert('Tidak ada pasien');
            		document.location.href = '../index1.php?page=$page';
            	</script>
            ";
           	exit;
		}

		$querycek3 = "SELECT ID_Pasien FROM Pasien";
		$check3 = mysqli_query($conn, $querycek3);
		$ada = 0;

		while ($row = mysqli_fetch_assoc($check3)){
			if($idPasien == $row["ID_Pasien"]){
				$ada = 1;
				
			}
		}

		if($ada != 1){
			echo "
            	<script>
            		alert('Pasien belum terdaftar');
            		document.location.href = '../index1.php?page=$page';
            	</script>
            ";
        	exit;
		}

		$querycek4 = "SELECT COUNT(*) AS total FROM Dokter";
		$check4 = mysqli_query($conn, $querycek4);
		$row4 = mysqli_fetch_assoc($check4);
		$totalDokter = $row4["total"];

		if($totalDokter == 0){
			echo "
            	<script>
            		alert('Dokter tidak ada');
            		document.location.href = '../index1.php?page=$page';
            	</script>
            ";
           	exit;
		}

		$querycek5 = "SELECT ID_Dokter FROM Dokter";
		$check5 = mysqli_query($conn, $querycek5);
		$ada2 = 0;

		while ($row = mysqli_fetch_assoc($check5)){
			if($idDokter == $row["ID_Dokter"]){
				$ada2 = 1;
			}
		}

		if($ada2 != 1 ){
			echo "
            	<script>
            		alert('Dokter belum terdaftar');
            		document.location.href = '../index1.php?page=$page';
            	</script>
            ";
        	exit;
		}
		

		$query1 = "INSERT INTO $Antrian (ID_Antrian, ID_Resepsionis, ID_Pasien, ID_Dokter, Tgl_Antrian, Status) VALUES ('$idAntrian', '$idResepsionis', '$idPasien', '$idDokter', '$tglAntrian', '$status')";

		if (mysqli_query($conn, $query1)) {
            echo "
            	<script>
            		alert('Antrian berhasil ditambahkan');
            		document.location.href = '../index1.php?page=$page';
            	</script>
            ";

        } else {
            echo "
            	<script>
            		alert('Antrian gagal ditambahkan');
            		document.location.href = '../index1.php?page=$page';
            	</script>
            ";
        }
	}


?>


<!DOCTYPE html>
<html>
<head>
    <title>Tambah Antrian</title>
    <link rel="stylesheet" type="text/css" href="../style2.css">

    <!-- Fitur auto complete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            var pilihanPasien = <?php echo json_encode($pilihanPasien); ?>;
            var pilihanDokter = <?php echo json_encode($pilihanDokter); ?>;

            // Inisialisasi autocompletion pada input teks
            $('#idPasien').autocomplete({
                source: pilihanPasien
            });

            $('#idDokter').autocomplete({
                source: pilihanDokter
            });
        });
    </script>
</head>

<body>
    <div class="form-container-antrian">
        <h1>Tambah Antrian</h1>
        <form action="" method="post">
            <div class="form-group-antrian">
                <label for="idPasien">ID Pasien</label>
                <input type="text" name="idPasien" size="50" id="idPasien" required>
            </div>
            <div class="form-group-antrian">
                <label for="idDokter">ID Dokter</label>
                <input type="text" name="idDokter" size="50" id="idDokter" required>
            </div>
            <div class="form-actions-antrian">
                <a href="../index1.php" class="btn-back-antrian">Kembali</a>
                <button type="reset" class="reset-button-antrian">Reset</button>
                <button type="submit" name="submit">Simpan Data</button>
            </div>
        </form>
    </div>
</body>
</html>
