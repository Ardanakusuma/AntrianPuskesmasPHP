<?php

    session_start();

    if ( !isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }

    $conn = mysqli_connect("localhost", "root", "", "antri");

    if (isset($_GET['id'])) {
        $idResepsionis = $_GET['id'];

        // Mengambil data pasien berdasarkan ID_Pasien
        $query = "SELECT * FROM Resepsionis WHERE ID_Resepsionis = $idResepsionis";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        $nama_r = $row['Nama_R'];
    }

    // Memperbarui data pasien
    if (isset($_POST['submit'])) {
        $namaBaru = $_POST['nama_r'];

        $queryUbah = "UPDATE Resepsionis SET Nama_R = '$namaBaru' WHERE ID_Resepsionis = $idResepsionis";

        var_dump($queryUbah);

        mysqli_query($conn, $queryUbah);

        if(mysqli_query($conn, $queryUbah)) {
            echo "
                <script>
                    alert('Data resepsionis berhasil diperbarui');
                    document.location.href = 'tampilresepsionis.php';
                </script>
            ";

        } else {
            echo "
                <script>
                    alert('Data resepsionis gagal diperbarui');
                    document.location.href = 'tampilresepsionis.php';
                </script>
            ";
        }

    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Ubah Data Resepsionis</title>
    <link rel="stylesheet" type="text/css" href="../style2.css">
</head>
<body>
    <div class="form-container-resepsionis">
        <h1>Edit Data Resepsionis</h1>
        <form action="" method="post">
            <div class="form-group-resepsionis">
                <label for="nama_r">Nama:</label>
                <input type="text" name="nama_r" value="<?= $nama_r; ?>" required><br>
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
