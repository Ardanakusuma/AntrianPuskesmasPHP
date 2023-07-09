<?php

	session_start();

	if ( !isset($_SESSION['login'])) {
		header("Location: login.php");
		exit;
	} 
	
	$conn = mysqli_connect("localhost", "root", "", "antri");

	if(isset($_POST['submit'])){
        $idResepsionis = $_GET['id'];
		$nama_r = htmlspecialchars($_POST['nama_r']);

		if($idResepsionis == ""){
            $idResepsionis = 1;
        }

		$query = "INSERT INTO Resepsionis (ID_Resepsionis, Nama_R) VALUES ('$idResepsionis','$nama_r')";

		if (mysqli_query($conn, $query)) {
            echo "
            	<script>
            		alert('Data resepsionis berhasil ditambahkan');
            		document.location.href = 'tampilresepsionis.php';
            	</script>
            ";

        } else {
            echo "
            	<script>
            		alert('Data resepsionis gagal ditambahkan');
            		document.location.href = 'tampilresepsionis.php';
            	</script>
            ";
        }
	}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Resepsionis</title>
    <link rel="stylesheet" type="text/css" href="../style2.css">
</head>
<body>
    <div class="form-container-resepsionis">
        <h1>Tambah Data Resepsionis</h1>
        <form action="" method="post">
            <div class="form-group-resepsionis">
                <label for="nama_r">Nama:</label>
                <input type="text" name="nama_r" id="nama_r" required>
            </div>

            <div class="form-actions-resepsionis">
                <a href="tampilresepsionis.php" class="btn-back-resepsionis">Kembali</a>
                <button type="reset" class="reset-button-resepsionis">Reset</button>
                <button type="submit" name="submit">Simpan Data</button>
            </div>
        </form>
    </div>
</body>
</html>

