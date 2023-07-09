<?php

session_start();

$conn = mysqli_connect("localhost", "root", "", "antri");

// database tambah no urut

$result = mysqli_query($conn, "SELECT * FROM Resepsionis");

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = $_POST['keyword'];

    // Menggunakan prepared statement untuk menghindari SQL Injection
    $query = "SELECT * FROM Resepsionis WHERE Nama_R LIKE ?";

    $stmt = mysqli_prepare($conn, $query);
    $keyword = "%$keyword%"; // Menambahkan wildcard pada keyword
    mysqli_stmt_bind_param($stmt, "s", $keyword);
    mysqli_stmt_execute($stmt);
    $resultcari = mysqli_stmt_get_result($stmt);
}

$querycek = "SELECT COUNT(*) AS total FROM Resepsionis;";
$check = mysqli_query($conn, $querycek);
$row = mysqli_fetch_assoc($check);
$jumlahData = $row["total"];

$result2 = mysqli_query($conn, "SELECT * FROM Resepsionis");
while ($row1 = mysqli_fetch_assoc($result2)){
    $idResepsionis = $row1["ID_Resepsionis"] + 1;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Resepsionis</title>
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
                <a href="tampilresepsionis.php">
                    <i data-feather="users" class="nav__item-icon"></i>
                    <span class="nav__item-text">Resepsionis</span>
                </a>
            </nav>
            <nav class="nav__item">
                <a href="../dokter/tampildokter.php">
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

        <div class="table-container-resepsionis">
            <table class="content-table-resepsionis">
                <thead>
                    <tr>
                        <th colspan="8">
                            <div class="table-header-resepsionis">
                                <div class="search-resepsionis">
                                    <span>Data Resepsionis</span>
                                    <form action="" method="post">
                                        <div class="searchBox-resepsionis">
                                            <input class="searchInput" type="text" name="keyword" autofocus placeholder="Cari nama resepsionis">
                                            <button type="submit" name="cari" class="searchButton">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="button-area">
                                    <a href="tambahresepsionis.php?id=<?= isset($idResepsionis) ? $idResepsionis : ''; ?>" class="btn-tambah-resepsionis">
                                        Tambah Data Resepsionis <i class="fas fa-light fa-circle-plus"></i>
                                    </a>
                                </div>

                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>No.</th>
                        <th>ID Resepsionis</th>
                        <th>Nama Resepsionis</th>
                        <th>Option</th>
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
                            <td><?= $row["ID_Resepsionis"]; ?></td>
                            <td><?= $row["Nama_R"]; ?></td>
                            <td>
                                <button class="edit-button" onclick="editResepsionis(<?= $row["ID_Resepsionis"]; ?>)">
                                    <i class="fa-solid fa-user-pen" style="color: #469e6f;"></i>
                                </button>
                                <button class="delete-button" onclick="hapusResepsionis(<?= $row["ID_Resepsionis"]; ?>)">
                                    <i class="fas fa-trash" style="color: #db2323;"></i>
                                </button>
                            </td>
                        </tr>
                        <?php $idResepsionis = $row["ID_Resepsionis"] + 1 ?>
                    <?php endwhile;?>
                </tbody>
            </table>
        </div>
        <br>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="../script.js"></script>

</body>

</html>