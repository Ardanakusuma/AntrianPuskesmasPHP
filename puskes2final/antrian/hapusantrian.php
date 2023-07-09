<?php 

	session_start();

	if (isset($_SESSION['login'])) {
	    $role = '1';
	}elseif (isset($_SESSION['loginDokter'])) {
	    $role = '2';
	}elseif (isset($_SESSION['loginPasien'])) {
	    $role = '3';
	}else{
	    header("Location: ../login.php");
	    exit;
	}

	$conn = mysqli_connect("localhost", "root", "", "antri");

	$ID_Antrian = $_GET['id'];
	$Antrian = $_GET['ruangan'];
	$ganti = $_GET['ganti'];
	$pagenow = $_GET['pagenow']; 

	if($Antrian == "Antrian"){
		$page = '1';
	}elseif($Antrian == "Antrian2"){
		$page = '2';
	}else{
		$page = '3';
	}

	$query = "DELETE FROM $Antrian WHERE ID_Antrian = $ID_Antrian";

	mysqli_query($conn, $query);

	if(mysqli_query($conn, $query)) {
		if($ganti == 1){
			echo "
			<script>
				document.location.href = '../index1.php?page=$pagenow';
			</script>
			";
		}else{
			echo "
			<script>
				alert('Antrian Selesai');
				document.location.href = '../index1.php?page=$page';
			</script>
			";
		}

	} else {
		echo "
			<script>
				alert('Antrian gagal diselesaikan');
				document.location.href = '../index1.php?page=$page';
			</script>
		";
	}

?>
