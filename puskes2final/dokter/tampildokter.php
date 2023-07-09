<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "antri");

// database tambah no urut

$result = mysqli_query($conn, "SELECT * FROM Dokter");

$querycek = "SELECT COUNT(*) AS total FROM Dokter;";
$check = mysqli_query($conn, $querycek);
$row = mysqli_fetch_assoc($check);
$totalRecords = $row["total"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = $_POST['keyword'];

    // Menggunakan prepared statement untuk menghindari SQL Injection
    $query = "SELECT * FROM Dokter WHERE Nama_D LIKE ?";

    $stmt = mysqli_prepare($conn, $query);
    $keyword = "%$keyword%"; // Menambahkan wildcard pada keyword
    mysqli_stmt_bind_param($stmt, "s", $keyword);
    mysqli_stmt_execute($stmt);
    $resultcari = mysqli_stmt_get_result($stmt);
}

$result2 = mysqli_query($conn, "SELECT * FROM Dokter");
while ($row1 = mysqli_fetch_assoc($result2)) {
    $idDokter = $row1["ID_Dokter"] + 1;
}


?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Dokter</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    </style>
</head>

<body>
    <div class="sidebar">
        <div class="hamburger-menu__container">
            <div class="hamburger-menu">
                <div class="hamburger-menu__line"></div>
                <div class="hamburger-menu__line"></div>
                <div class="hamburger-menu__line"></div>
            </div>
        </div>
        <nav class="nav">
            <nav class="nav__item">
                <a href="../index1.php">
                    <i data-feather="home" class="nav__item-icon"></i>
                    <span class="nav__item-text">Beranda</span>
                </a>
            </nav>
            <nav class="nav__item">
                <a href="../resepsionis/tampilresepsionis.php">
                    <i data-feather="users" class="nav__item-icon"></i>
                    <span class="nav__item-text">Resepsionis</span>
                </a>
            </nav>
            <nav class="nav__item">
                <a href="tampildokter.php">
                    <i data-feather="user" class="nav__item-icon"></i>
                    <span class="nav__item-text">Dokter</span>
                </a>
            </nav>
            <nav class="nav__item">
                <a href="../pasien/tampilpasien.php">
                    <i data-feather="file-text" class="nav__item-icon"></i>
                    <span class="nav__item-text">Data Pasien</span>
                </a>
            </nav>
        </nav>
    </div>
    <div id="navigasilog">
        <a href="../logout.php">Keluar
            <i class="fa fa-sign-out"></i>
        </a>
    </div>

    <div id="content">
        <br>
        <?php if ($totalRecords == 0) { ?>
            <h2>Tidak ada data dokter</h2>

            <!-- PERBAIKI CSS -->

            <div class="button-area-null">
                <style>
                    .add-link i {
                        font-size: 50px;
                        color: #3C584C;
                    }
                </style>
            </div>
            <a href="tambahdokter.php?id=<?= isset($idDokter) ? $idDokter : ''; ?>" class="add-link">
                <i class="fa-solid fa-person-circle-plus"></i>
            </a>
    </div>
    <br>
<?php } else { ?>
    <div class="table-container-dokter">
        <table class="content-table-dokter">
            <thead>
                <tr>
                    <th colspan="8">
                        <div class="table-header-dokter">
                            <div class="search-area">
                                <span>Data Dokter</span>
                                <form action="" method="post">
                                    <div class="searchBox-dokter">
                                        <input class="searchInput" type="text" name="keyword" autofocus placeholder="Cari nama dokter">
                                        <button type="submit" name="cari" class="searchButton">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="button-area">
                                <a href="tambahdokter.php?id=<?= isset($idDokter) ? $idDokter : ''; ?>" class="btn-tambah-dokter">
                                    Tambah Data Dokter <i class="fas fa-light fa-circle-plus"></i>
                                </a>
                            </div>
                        </div>
                    </th>
                <tr>
                    <th>No.</th>
                    <th>ID Dokter</th>
                    <th>Nama Dokter</th>
                    <th>Spesialis</th>
                    <th>Jadwal</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php $nomor = 1 ?>

                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $data = $resultcari;
                } else {
                    $data = $result;
                } ?>

                <?php while ($row = mysqli_fetch_assoc($data)) : ?>
                    <tr>
                        <td><?= $nomor++; ?></td>
                        <td><?= $row["ID_Dokter"]; ?></td>
                        <td><?= $row["Nama_D"]; ?></td>
                        <td><?= $row["Spesialis"]; ?></td>
                        <td><?= $row["Jadwal"]; ?></td>
                        <td>
                            <button class="edit-button" onclick="editDokter(<?= $row["ID_Dokter"]; ?>)">
                                <i class="fa-solid fa-user-pen" style="color: #469e6f;"></i>
                            </button>
                            <button class="delete-button" onclick="hapusDokter(<?= $row["ID_Dokter"]; ?>)">
                                <i class="fas fa-trash" style="color: #db2323;"></i>
                            </button>
                        </td>
                    </tr>
                    <?php $idDokter = $row["ID_Dokter"] + 1 ?>
                <?php endwhile; ?>
            </tbody>

        </table>
    </div>
<?php } ?>
<br>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="../script.js"></script>

</body>

</html>