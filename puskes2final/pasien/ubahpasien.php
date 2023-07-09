<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "antri");

if (isset($_GET['id'])) {
    $idPasien = $_GET['id'];

    // Mengambil data pasien berdasarkan ID_Pasien
    $query = "SELECT * FROM Pasien WHERE ID_Pasien = $idPasien";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $nama_p = $row['Nama_P'];
    $alamat = $row['Alamat'];
    $domisili = $row['Domisili'];
    $tglLahir = $row['Tgl_lahir'];
    $noHP = $row['No_HP'];
    $jenisKelamin = $row['Jenis_Kelamin'];
    $ktp_kkLama = $row['KTP_KK'];
}

// Memperbarui data pasien
if (isset($_POST['submit'])) {
    $namaBaru = $_POST['nama_p'];
    $alamatBaru = $_POST['alamat'];
    $domisiliBaru = $_POST['domisili'];
    $tglLahirBaru = $_POST['tgl_lahir'];
    $noHPBaru = $_POST['no_hp'];
    $jenisKelaminBaru = $_POST['jenis_kelamin'];
    $ktp_kkLama = $_POST['ktp_kkLama'];

    if ($_FILES['ktp_kk']['error'] == 4) {
        $ktp_kk = $ktp_kkLama;
    } else {

        //upload ktp/kk
        $namaFile = $_FILES['ktp_kk']['name'];
        $directory = $_FILES['ktp_kk']['tmp_name'];

        //cek gambar
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));

        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            echo "
                    <script>
                        alert('File yang diupload bukan gambar');
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

        $ktp_kk = $namaFileBaru;
    }


    $queryUbah = "UPDATE Pasien SET Nama_P = '$namaBaru', Alamat = '$alamatBaru', Domisili = '$domisiliBaru', Tgl_lahir = '$tglLahirBaru', No_HP = '$noHPBaru', Jenis_Kelamin = '$jenisKelaminBaru',  KTP_KK = '$ktp_kk' WHERE ID_Pasien = $idPasien";

    mysqli_query($conn, $queryUbah);

    if (mysqli_query($conn, $queryUbah)) {
        echo "
                <script>
                    alert('Data pasien berhasil diperbarui');
                    document.location.href = 'tampilpasien.php';
                </script>
            ";
    } else {
        echo "
                <script>
                    alert('Data pasien gagal diperbarui');
                    document.location.href = 'tampilpasien.php';
                </script>
            ";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Ubah Data Pasien</title>
    <link rel="stylesheet" type="text/css" href="../style2.css">
    </style>
</head>

<body>
    <div class="form-container-pasien">
        <h1>Edit Data Pasien</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-column-pasien">
                <div class="form-group-pasien">
                    <label for="nama_p">Nama</label>
                    <input type="text" name="nama_p" value="<?php echo isset($nama_p) ? $nama_p : ''; ?>" required>
                </div>

                <div class="form-group-pasien">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" value="<?php echo isset($alamat) ? $alamat : ''; ?>" required>
                </div>
                <div class="form-group-pasien">
                    <label for="no_hp">No. HP</label>
                    <input type="text" name="no_hp" value="<?php echo isset($noHP) ? $noHP : ''; ?>" required>
                </div>
                <a href="tampilpasien.php" class="btn-back-pasien">Kembali</a>

            </div>
            <div class="form-column-pasien">
                <div class="form-group-pasien">
                    <label for="tgl_lahir">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" value="<?php echo isset($tglLahir) ? $tglLahir : ''; ?>" required>
                </div>
                <div class="form-group-pasien">
                    <tr>
                        <td><label for="jenis_kelamin">Jenis Kelamin</label></td>
                        <td><select type="text" name="jenis_kelamin" required>
                                <option value="L" <?php if ($jenisKelamin == 'L') echo 'selected'; ?>>Laki-laki</option>
                                <option value="P" <?php if ($jenisKelamin == 'P') echo 'selected'; ?>>Perempuan</option>
                            </select></td>
                    </tr>
                </div>
                <div class="form-group-pasien">
                    <label for="ktp_kk">Foto KTP / KK:</label>
                    <label for="images" class="drop-container">
                        </td>
                        <input type="file" name="ktp_kk" id="ktp_kk"></td>
                    </label>
                </div>

                <div class="form-actions-pasien">
                    <button type="reset" class="reset-button-pasien">Reset</button>
                    <button type="submit" name="submit">Simpan Data</button>
                </div>
                <input type="hidden" name="ktp_kkLama" value="<?php echo isset($ktp_kkLama) ? $ktp_kkLama : ''; ?>" required>
            </div>
        </form>
    </div>
</body>

</html>