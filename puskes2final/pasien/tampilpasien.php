<?php

session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../login.php");
  exit;
}

$keyword = '';

$conn = mysqli_connect("localhost", "root", "", "antri");

//pagination
$jumlahDataPerPage = 10;

$querycek = "SELECT COUNT(*) AS total FROM Pasien;";
$check = mysqli_query($conn, $querycek);
$row = mysqli_fetch_assoc($check);
$jumlahData = $row["total"];

$jumlahHalaman = ceil($jumlahData / $jumlahDataPerPage);

if (isset($_GET['page'])) {
  $halamanAktif = $_GET['page'];
} else {
  $halamanAktif = 1;
}

$awalData = ($jumlahDataPerPage * $halamanAktif) - $jumlahDataPerPage;

$result = mysqli_query($conn, "SELECT * FROM Pasien LIMIT $awalData, $jumlahDataPerPage");


$querycek = "SELECT COUNT(*) AS total FROM Pasien;";
$check = mysqli_query($conn, $querycek);
$row = mysqli_fetch_assoc($check);
$totalRecords = $row["total"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $keyword = $_POST['keyword'];

  if ($keyword != "") {
    // Menggunakan prepared statement untuk menghindari SQL Injection
    $query = "SELECT * FROM Pasien WHERE Nama_P LIKE ?";

    $stmt = mysqli_prepare($conn, $query);
    $keyword = "%$keyword%"; // Menambahkan wildcard pada keyword
    mysqli_stmt_bind_param($stmt, "s", $keyword);
    mysqli_stmt_execute($stmt);
    $resultcari = mysqli_stmt_get_result($stmt);
  } else {
    $resultcari = mysqli_query($conn, "SELECT * FROM Pasien LIMIT $awalData, $jumlahDataPerPage");
  }
}

$result2 = mysqli_query($conn, "SELECT * FROM Pasien");

while ($row = mysqli_fetch_assoc($result2)) {
  $idPasien = $row["ID_Pasien"] + 1;
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Data Pasien</title>
  <link rel="stylesheet" type="text/css" href="../style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
        <a href="../dokter/tampildokter.php">
          <i data-feather="user" class="nav__item-icon"></i>
          <span class="nav__item-text">Dokter</span>
        </a>
      </nav>
      <nav class="nav__item">
        <a href="tampilpasien.php">
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

    <?php if ($totalRecords == 0) : ?>
      <h2>Tidak ada data Pasien</h2>

      <!-- PERBAIKI CSS -->

      <div class="button-area-null">
        <style>
          .add-link i {
            font-size: 50px;
            color: #3C584C;
          }
        </style>
      </div>
      <a href="tambahpasien.php?id=<?= isset($idPasien) ? $idPasien : ''; ?>" class="add-link">
        <i class="fa-solid fa-person-circle-plus"></i>
      </a>
      <br>
    <?php else : ?>
      <div class="table-container-pasien">
        <table class="content-table-pasien">
          <thead>
            <tr>
              <th colspan="8">
                <div class="table-header-pasien">
                  <div class="search-area">
                    <span>Data Pasien</span>
                    <form action="" method="post">
                      <div class="searchBox-pasien">
                        <input class="searchInput" type="text" name="keyword" autofocus placeholder="Cari nama pasien">
                        <button type="submit" name="cari" class="searchButton">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </form>
                  </div>
                  <div class="button-area">
                    <a href="tambahpasien.php?id=<?= $idPasien; ?>" class="btn-tambah-pasien">
                      Tambah Data Pasien <i class="fas fa-light fa-circle-plus"></i>
                    </a>
                  </div>
                </div>
              </th>
            </tr>
            <tr>
              <th>No.</th>
              <th>ID Pasien</th>
              <th>Nama Pasien</th>
              <th>Jenis Kelamin</th>
              <th>Alamat</th>
              <th>Tgl Lahir</th>
              <th>No. HP</th>
              <th>Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php $nomor = $awalData + 1 ?>
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              $data = $resultcari;
            } else {
              $data = $result;
            } ?>
            <?php while ($row = mysqli_fetch_assoc($data)) : ?>
              <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row["ID_Pasien"]; ?></td>
                <td><?= $row["Nama_P"]; ?></td>
                <td><?= $row["Jenis_Kelamin"]; ?></td>
                <td><?= $row["Alamat"]; ?></td>
                <td><?= $row["Tgl_lahir"]; ?></td>
                <td><?= $row["No_HP"]; ?></td>
                <td>
                  <button class="edit-button" onclick="editPasien(<?= $row["ID_Pasien"]; ?>)">
                    <i class="fa-solid fa-user-pen" style="color: #469e6f;"></i>
                  </button>
                  <button class="delete-button" onclick="hapusPasien(<?= $row["ID_Pasien"]; ?>)">
                    <i class="fas fa-trash" style="color: #db2323;"></i>
                  </button>
                </td>
              </tr>
              <?php $idPasien = $row["ID_Pasien"] + 1 ?>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    <?php endif; ?>
    <div class="pagination-container">
      <?php if ($jumlahData > $jumlahDataPerPage && ($keyword == NULL || $keyword == "")) : ?>
        <?php if ($halamanAktif > 1) : ?>
          <a href="?page=<?= $halamanAktif - 1 ?>" class="pagination-arrow"><i class="fa-solid fa-chevron-left"></i> <span class="pagination-text"></span></a>
        <?php endif; ?>

        <div class="pagination">
          <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
            <?php if ($i == $halamanAktif) : ?>
              <a href="?page=<?= $i ?>" class="active"><?= $i ?></a>
            <?php elseif (($i >= $halamanAktif - 2) && ($i <= $halamanAktif + 2)) : ?>
              <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php elseif ($i == 1) : ?>
              <a href="?page=<?= $i ?>">1</a>
              <span>...</span>
            <?php elseif ($i == $jumlahHalaman) : ?>
              <span>...</span>
              <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
          <?php endfor; ?>
        </div>

        <?php if ($halamanAktif < $jumlahHalaman) : ?>
          <a href="?page=<?= $halamanAktif + 1 ?>" class="pagination-arrow"><span class="pagination-text"></span> <i class="fa-solid fa-chevron-right"></i></a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src="../script.js"></script>
</body>

</html>