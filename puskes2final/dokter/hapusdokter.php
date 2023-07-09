<?php 

	session_start();

	if ( !isset($_SESSION['login'])) {
		header("Location: ../login.php");
		exit;
	} 

	$conn = mysqli_connect("localhost", "root", "", "antri");

	$ID_Dokter = $_GET['id'];

	$query1 = "DELETE FROM Antrian WHERE ID_Dokter = $ID_Dokter";

	$query = "DELETE FROM Dokter WHERE ID_Dokter = $ID_Dokter";

	mysqli_query($conn, $query1);
	mysqli_query($conn, $query);

	if(mysqli_query($conn, $query)) {
		echo "
			<script>
				alert('Data dokter berhasil dihapus');
				document.location.href = 'tampildokter.php';
			</script>
		";

	} else {
		echo "
			<script>
				alert('Data dokter gagal dihapus');
				document.location.href = 'tampildokter.php';
			</script>
		";
	}

?>