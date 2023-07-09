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

	$querycek = "SELECT COUNT(*) AS total FROM $Antrian;";
	$check = mysqli_query($conn, $querycek);
	$row = mysqli_fetch_assoc($check);
	$totalRecords = $row["total"];

	if (isset($_GET['id']) && isset($_GET['urutan'])) {
        $idAntrian = $_GET['id'];
        $urutan = $_GET['urutan'];

		// QUERY UPDATE UBAH URUTAN ANTRIAN

		$query = "UPDATE $Antrian SET ID_Antrian = '0' WHERE ID_Antrian = $idAntrian";
		mysqli_query($conn, $query);

		if($urutan <= $totalRecords){

			if(intval($idAntrian) > intval($urutan)){

				if($urutan==1){
					$queryst = "UPDATE $Antrian SET Status = 'Menunggu' WHERE ID_Antrian = '1'";
					mysqli_query($conn, $queryst);

					$queryst2 = "UPDATE $Antrian SET Status = 'Diperiksa' WHERE ID_Antrian = '0'";
					mysqli_query($conn, $queryst2);
				}

				// Urut bawah ke atas
				$jumlah = $idAntrian - $urutan;
				$set = $idAntrian - 1;

				for ($i = $jumlah; $i > 0; $i--) { 

					$set2 = $urutan + $i;

					$queryganti = "UPDATE $Antrian SET ID_Antrian = $set2 WHERE ID_Antrian = $set";
					mysqli_query($conn, $queryganti);
					var_dump($queryganti);

					$set --;
				}

				if($urutan==1 && $idAntrian!=1){
					if($Antrian == "Antrian"){
						$_SESSION['start'] = true;
					}elseif($Antrian == "Antrian2"){
						$_SESSION['start2'] = true;
					}else{
						$_SESSION['start3'] = true;
					}
				}

			}elseif(intval($idAntrian) < intval($urutan)){

				if($idAntrian==1){
					$queryst = "UPDATE $Antrian SET Status = 'Menunggu' WHERE ID_Antrian = '0'";
					mysqli_query($conn, $queryst);

					$queryst2 = "UPDATE $Antrian SET Status = 'Diperiksa' WHERE ID_Antrian = '2'";
					mysqli_query($conn, $queryst2);
				}

				// Urut atas ke bawah
				$jumlah = $urutan - $idAntrian;
				$set = $idAntrian + 1;

				for ($i = 0; $i < $jumlah; $i++) { 

					$set2 = $urutan - $jumlah + $i;

					$queryganti = "UPDATE $Antrian SET ID_Antrian = $set2 WHERE ID_Antrian = $set";
					mysqli_query($conn, $queryganti);
					var_dump($queryganti);

					$set ++;
				}

				if($idAntrian==1 && $urutan!=1 ){
					if($Antrian == "Antrian"){
						$_SESSION['start'] = true;
					}elseif($Antrian == "Antrian2"){
						$_SESSION['start2'] = true;
					}else{
						$_SESSION['start3'] = true;
					}
				}

			}else{
				echo "
				<script>
					alert('Antrian tetap');
					document.location.href = '../index1.php?page=$page';
					exit;
				</script>
				";
			}

		}else{
			echo "
			<script>
				alert('Antrian tetap');
				document.location.href = '../index1.php?page=$page';
				exit;
			</script>
			";
			$urutan = $idAntrian;
		}

		$query2 = "UPDATE $Antrian SET ID_Antrian = '$urutan' WHERE ID_Antrian = '0'";
		mysqli_query($conn, $query2);

		if (mysqli_query($conn, $query2)) {
			echo "
				<script>
					alert('Antrian berhasil diubah');
					document.location.href = '../index1.php?page=$page';
					exit;
				</script>
			";
		} else {
			echo "
				<script>
					alert('Antrian gagal diubah');
					document.location.href = '../index1.php?page=$page';
					exit;
				</script>
			";
		}
    } 


    if (isset($_GET['id']) && isset($_GET['status'])) {
    	$idAntrian = $_GET['id'];
        $status = $_GET['status'];

        $conn = mysqli_connect("localhost", "root", "", "antri");

		$querymax = "SELECT COUNT(*) AS total FROM $Antrian";
		$result = mysqli_query($conn, $querymax);
		$row = mysqli_fetch_assoc($result);
    	$max = $row['total'];

    	$querystatus = "SELECT Status FROM $Antrian WHERE ID_Antrian = $idAntrian";
		$result2 = mysqli_query($conn, $querystatus);
		$row2 = mysqli_fetch_assoc($result2);
    	$Statnow = $row2['Status'];

        if($status == 1){
        	// Menunggu

        	if ($idAntrian == 1 && $totalRecords >1) {

        		$query1 = "UPDATE $Antrian SET ID_Antrian = 0 WHERE ID_Antrian = $idAntrian";
				mysqli_query($conn, $query1);
				var_dump($query1);

				// Urut atas ke bawah
				$jumlah = $max - $idAntrian;
				$set = $idAntrian + 1;

				for ($i = 0; $i < $jumlah; $i++) { 

					$set2 = $max - $jumlah + $i;

					$queryganti = "UPDATE $Antrian SET ID_Antrian = $set2 WHERE ID_Antrian = $set";
					mysqli_query($conn, $queryganti);
					var_dump($queryganti);

					$set ++;
				}

				$query2 = "UPDATE $Antrian SET Status = 'Menunggu' WHERE ID_Antrian = '0'";
	        	mysqli_query($conn, $query2);
	        	var_dump($query2);

	        	$query3 = "UPDATE $Antrian SET Status = 'Diperiksa' WHERE ID_Antrian = '1'";
	        	mysqli_query($conn, $query3);
	        	var_dump($query3);

	        	$query4 = "UPDATE $Antrian SET ID_Antrian = $max WHERE ID_Antrian = '0'";
				mysqli_query($conn, $query4);
				var_dump($query4);

				if($Antrian == "Antrian"){
					$_SESSION['start'] = true;
				}elseif($Antrian == "Antrian2"){
					$_SESSION['start2'] = true;
				}else{
					$_SESSION['start3'] = true;
				}

	        	if (mysqli_query($conn, $query4)) {
					echo "
						<script>
							alert('Status berhasil diubah');
							document.location.href = '../index1.php?page=$page';
							exit;
						</script>
					";

				} else {
					echo "
						<script>
							alert('Status gagal diubah');
							document.location.href = '../index1.php?page=$page';
							exit;
						</script>
					";
				}
        	} else {
        		echo "
					<script>
						alert('Status tetap');
						document.location.href = '../index1.php?page=$page';
						exit;
					</script>
				";
        	}

        }elseif ($status == 2) {
        	// Diperiksa
        	if ($idAntrian != 1) {
	        	$query = "UPDATE $Antrian SET ID_Antrian = '0' WHERE ID_Antrian = $idAntrian";
				mysqli_query($conn, $query);
				var_dump($query);

				$query2 = "UPDATE $Antrian SET Status = 'Menunggu' WHERE ID_Antrian = '1'";
				mysqli_query($conn, $query2);
				var_dump($query2);

				$jumlah = $idAntrian - 1;
				$set = $jumlah;

				for ($i = $jumlah; $i > 0; $i--) { 

					$set2 = 1 + $i;

					$queryganti = "UPDATE $Antrian SET ID_Antrian = '$set2' WHERE ID_Antrian = '$set'";
					mysqli_query($conn, $queryganti);
					var_dump($queryganti);

					$set --;
				}

				$query3 = "UPDATE $Antrian SET ID_Antrian = '1', Status = 'Diperiksa' WHERE ID_Antrian = '0'";
				mysqli_query($conn, $query3);
				var_dump($query3);

				if($Antrian == "Antrian"){
					$_SESSION['start'] = true;
				}elseif($Antrian == "Antrian2"){
					$_SESSION['start2'] = true;
				}else{
					$_SESSION['start3'] = true;
				}

				if (mysqli_query($conn, $query3)) {
					echo "
						<script>
							alert('Status berhasil diubah');
							document.location.href = '../index1.php?page=$page';
							exit;
						</script>
					";
				} else {
					echo "
						<script>
							alert('Status gagal diubah');
							document.location.href = '../index1.php?page=$page';
							exit;
						</script>
					";
				}

			} else {
        		echo "
					<script>
						alert('Status tetap');
						document.location.href = '../index1.php?page=$page';
						exit;
					</script>
				";
        	}

        }elseif ($status == 3){
        	// Selesai

        	if($idAntrian==1){
        		$query = "UPDATE $Antrian SET ID_Antrian = '0' WHERE ID_Antrian = $idAntrian";
				mysqli_query($conn, $query);
				var_dump($query);

				$query2 = "UPDATE $Antrian SET Status = 'Diperiksa' WHERE ID_Antrian = '2'";
				mysqli_query($conn, $query2);
				var_dump($query2);

				$jumlah = $max - 1;
				$set = $idAntrian + 1;

				for ($i = 0; $i < $jumlah; $i++) { 

					$set2 = 1 + $i;

					$queryganti = "UPDATE $Antrian SET ID_Antrian = $set2 WHERE ID_Antrian = $set";
					mysqli_query($conn, $queryganti);

					$set ++;
				}

				if($Antrian == "Antrian"){
					$_SESSION['start'] = true;
				}elseif($Antrian == "Antrian2"){
					$_SESSION['start2'] = true;
				}else{
					$_SESSION['start3'] = true;
				}

        	} else {
        		$query = "UPDATE $Antrian SET ID_Antrian = 0 WHERE ID_Antrian = $idAntrian";
				mysqli_query($conn, $query);
				var_dump($query);

				$jumlah = $max - $idAntrian;
				$set = $idAntrian + 1;

				for ($i = 0; $i < $jumlah; $i++) { 

					$set2 = $idAntrian + $i;

					$queryganti = "UPDATE $Antrian SET ID_Antrian = $set2 WHERE ID_Antrian = $set";
					mysqli_query($conn, $queryganti);
					var_dump($queryganti);

					$set ++;
				}
        	}

        	header("Location: hapusantrian.php?id=0&ruangan=$Antrian&page=$page&ganti=$ganti&pagenow=$pagenow");

        }else{

        	header("Location: ../index1.php");

        }
    } else {
    	echo "
			<script>
				alert('Antrian tetap');
				document.location.href = '../index1.php?page=$page';
				exit;
			</script>
		";
    }

	

?>