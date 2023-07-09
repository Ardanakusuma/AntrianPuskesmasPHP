<?php 

	session_start();

	if ( !isset($_SESSION['login'])) {
		header("Location: ../login.php");
		exit;
	} 

	$conn = mysqli_connect("localhost", "root", "", "antri");

	$ID_Pasien = $_GET['id'];

	$query1 = "DELETE FROM Antrian WHERE ID_Pasien = $ID_Pasien";

	$query = "DELETE FROM Pasien WHERE ID_Pasien = $ID_Pasien";

	mysqli_query($conn, $query1);
	mysqli_query($conn, $query);

	if(mysqli_query($conn, $query)) {
		echo "
			<script>
				alert('Data pasien berhasil dihapus');
				document.location.href = 'tampilpasien.php';
			</script>
		";

	} else {
		echo "
			<script>
				alert('Data pasien gagal dihapus');
				document.location.href = 'tampilpasien.php';
			</script>
		";
	}

?>