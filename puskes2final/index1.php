<?php

session_start();
$idAntrian = '';

if (isset($_SESSION['login'])) {
    $role = '1';
} elseif (isset($_SESSION['loginDokter'])) {
    $role = '2';
} elseif (isset($_SESSION['loginPasien'])) {
    $role = '3';
} else {
    header("Location: login.php");
    exit;
}


$conn = mysqli_connect("localhost", "root", "", "antri");

// database tambah no urut

if (!isset($_GET['page']) || $_GET['page'] == 1) {
    $Antrian = "Antrian";
    $page = 1;
} elseif ($_GET['page'] == 2) {
    $Antrian = "Antrian2";
    $page = 2;
} elseif ($_GET['page'] == 3) {
    $Antrian = "Antrian3";
    $page = 3;
}

$querycek = "SELECT COUNT(*) AS total FROM $Antrian;";
$check = mysqli_query($conn, $querycek);
$row = mysqli_fetch_assoc($check);
$totalRecords = $row["total"];

$querycek1 = "SELECT COUNT(*) AS total FROM Antrian;";
$check1 = mysqli_query($conn, $querycek1);
$row1 = mysqli_fetch_assoc($check1);
$totalRecords1 = $row1["total"];

$querycek2 = "SELECT COUNT(*) AS total FROM Antrian2;";
$check2 = mysqli_query($conn, $querycek2);
$row2 = mysqli_fetch_assoc($check2);
$totalRecords2 = $row2["total"];

$querycek3 = "SELECT COUNT(*) AS total FROM Antrian3;";
$check3 = mysqli_query($conn, $querycek3);
$row3 = mysqli_fetch_assoc($check3);
$totalRecords3 = $row3["total"];


$result = mysqli_query($conn, "SELECT A.ID_Antrian, P.ID_Pasien, P.Nama_P, P.No_HP, P.KTP_KK, D.Spesialis, A.Status
                                FROM $Antrian AS A
                                LEFT JOIN Pasien AS P ON A.ID_Pasien = P.ID_Pasien
                                LEFT JOIN Dokter AS D ON A.ID_Dokter = D.ID_Dokter");

// Set waktu mulai tunggu jika belum diset ruangan 1
if (!isset($_SESSION['start_time'])) {
    $_SESSION['start_time'] = time();
}

// Set waktu mulai tunggu jika belum diset ruangan 2
if (!isset($_SESSION['start_time2'])) {
    $_SESSION['start_time2'] = time();
}

// Set waktu mulai tunggu jika belum diset ruangan 3
if (!isset($_SESSION['start_time3'])) {
    $_SESSION['start_time3'] = time();
}

// Cek apakah pasien ditambahkan ruangan 1
if (isset($_SESSION['start'])) {
    $_SESSION['start_time'] = time();
    unset($_SESSION['start']);
}

// Cek apakah pasien ditambahkan ruangan 2
if (isset($_SESSION['start2'])) {
    $_SESSION['start_time2'] = time();
    unset($_SESSION['start2']);
}

// Cek apakah pasien ditambahkan ruangan 3
if (isset($_SESSION['start3'])) {
    $_SESSION['start_time3'] = time();
    unset($_SESSION['start3']);
}

// Durasi timer dalam detik
$duration = 15 * 60;

// Hitung sisa waktu berdasarkan waktu mulai dan durasi ruangan 1
$elapsed_time = time() - $_SESSION['start_time'];
$remaining_time = max(0, $duration - $elapsed_time);

// Cek apakah timer telah selesai ruangan 1
$is_timer_finished = ($remaining_time < 1);

if ($is_timer_finished && $totalRecords1 != 0) {
    header("Location: antrian/ubahantrian.php?id=1&status=3&ruangan=Antrian&ganti=1&pagenow=$page");
}

// Hitung sisa waktu berdasarkan waktu mulai dan durasi ruangan 2
$elapsed_time2 = time() - $_SESSION['start_time2'];
$remaining_time2 = max(0, $duration - $elapsed_time2);

// Cek apakah timer telah selesai ruangan 2
$is_timer_finished2 = ($remaining_time2 < 1);

if ($is_timer_finished2 && $totalRecords2 != 0) {
    header("Location: antrian/ubahantrian.php?id=1&status=3&ruangan=Antrian2&ganti=1&pagenow=$page");
}

// Hitung sisa waktu berdasarkan waktu mulai dan durasi ruangan 3
$elapsed_time3 = time() - $_SESSION['start_time3'];
$remaining_time3 = max(0, $duration - $elapsed_time3);

// Cek apakah timer telah selesai ruangan 3
$is_timer_finished3 = ($remaining_time3 < 1);

if ($is_timer_finished3 && $totalRecords3 != 0) {
    header("Location: antrian/ubahantrian.php?id=1&status=3&ruangan=Antrian3&ganti=1&pagenow=$page");
}

if ($Antrian == "Antrian") {
    $timer = $remaining_time;
} elseif ($Antrian == "Antrian2") {
    $timer = $remaining_time2;
} else {
    $timer = $remaining_time3;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = $_POST['keyword'];

    // Menggunakan prepared statement untuk menghindari SQL Injection
    $query = "SELECT * FROM $Antrian AS A
              LEFT JOIN Pasien ON A.ID_Pasien = Pasien.ID_Pasien
              LEFT JOIN Dokter ON A.ID_Dokter = Dokter.ID_Dokter
              LEFT JOIN Resepsionis ON A.ID_Resepsionis = Resepsionis.ID_Resepsionis 
              WHERE Nama_P LIKE ? OR A.ID_Pasien LIKE ?";

    $stmt = mysqli_prepare($conn, $query);
    $keyword = "%$keyword%"; // Menambahkan wildcard pada keyword
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $resultcari = mysqli_stmt_get_result($stmt);
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Halaman Antrian</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php if ($role == '1') { ?>
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
                    <a href="index1.php">
                        <i data-feather="home" class="nav__item-icon"></i>
                        <span class="nav__item-text">Beranda</span>
                    </a>
                </nav>
                <nav class="nav__item">
                    <a href="resepsionis/tampilresepsionis.php">
                        <i data-feather="users" class="nav__item-icon"></i>
                        <span class="nav__item-text">Resepsionis</span>
                    </a>
                </nav>
                <nav class="nav__item">
                    <a href="dokter/tampildokter.php">
                        <i data-feather="user" class="nav__item-icon"></i>
                        <span class="nav__item-text">Dokter</span>
                    </a>
                </nav>
                <nav class="nav__item">
                    <a href="pasien/tampilpasien.php">
                        <i data-feather="file-text" class="nav__item-icon"></i>
                        <span class="nav__item-text">Data Pasien</span>
                    </a>
                </nav>
            </nav>
        </div>
    <?php } ?>
    <body>
    <div id="navigasilog">
        <a href="logout.php">Keluar
            <i class="fa fa-sign-out"></i>
        </a>
    </div>

    <div id="navigasi">
        <ul>
            <li><a href="?page=1">Ruangan 1</a></li>
            <li><a href="?page=2">Ruangan 2</a></li>
            <li><a href="?page=3">Ruangan 3</a></li>
        </ul>
    </div>

    <div class="contentnav">
        <div class="nav-section">
            <?php
            if (!isset($_GET['page']) || $_GET['page'] == 1) {
                // Isi konten 1
                echo "<h2>Ruangan 1: Pemeriksaan Umum</h2>";
                $Antrian = "Antrian";
            } elseif ($_GET['page'] == 2) {
                // Isi konten 2
                echo "<h2>Ruangan 2: KIA, KB, dan Imunisasi</h2>";
                $Antrian = "Antrian2";
            } elseif ($_GET['page'] == 3) {
                // Isi konten 3
                echo "<h2>Ruangan 3: Kesehatan Gigi dan Mulut</h2>";
                $Antrian = "Antrian3";
            } else {
                echo "<h2>Ruangan tidak ada</h2>";
            }
            ?>
        </div>
    </div>

    <div id="content">
        <?php if ($totalRecords == 0) { ?>

            <h1>Tidak ada antrian</h1>
            <br>

        <?php } else { ?>
            <div class="table-container-antri">
                <table class="content-table-antri">
                    <thead>
                        <tr>
                            <th colspan="7">
                                <div class="table-header-antri">
                                    <div class="search-area">
                                        <span>Data Antrian</span>
                                        <form action="" method="post">
                                            <div class="searchBox-antri">
                                                <input class="searchInput" type="text" name="keyword" autofocus placeholder="Cari nama pasien">
                                                <button type="submit" name="cari" class="searchButton">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>No.</th>
                            <th>ID Pasien</th>
                            <th>Nama Pasien</th>
                            <th>Berobat</th>
                            <th>Status</th>
                            <th>Waktu</th>
                            <?php if ($role == '1') { ?>
                                <th>Opsi</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomor = 1;
                        $tambah = 15 * 60;

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $data = $resultcari;
                        } else {
                            $data = $result;
                        }

                        while ($row = mysqli_fetch_assoc($data)) :
                            $hour = floor($timer / 3600);
                            $minutes = floor($timer / 60);
                            $seconds = $timer % 60;
                        ?>
                            <tr>
                                <td><?= $nomor++; ?></td>
                                <td><?= $row["ID_Pasien"]; ?></td>
                                <td><?= $row["Nama_P"]; ?></td>
                                <td><?= $row["Spesialis"]; ?></td>
                                <td><?= $row["Status"]; ?></td>
                                <?php if($timer < 60){ ?>
                                    <td><?= $seconds; ?> Detik</td>

                                <?php }elseif($timer < 3600){ ?>
                                    <td><?= $minutes; ?> Menit</td>

                                <?php }else{ ?>
                                    <td><?= $hour; ?> Jam <?php if($minutes%60!=0){echo $minutes%60; echo " Menit";} ?> </td>
                                <?php } ?>

                                <?php if ($role == '2') { ?>
                                    <td>
                                        <button class="btn-ubah-antrian" onclick="openFloatWindow('<?= $row["ID_Antrian"]; ?>',
                                                                        '<?= $row["Nama_P"]; ?>',
                                                                        '<?= $row["No_HP"]; ?>',
                                                                        '<?= $row["Spesialis"]; ?>',
                                                                        '<?= $row["Status"]; ?>',
                                                                        '<?= $row["KTP_KK"]; ?>',
                                                                        '<?= $Antrian ?>'
                                                                        )">Detail
                                                                    </button>
                                    </td>
                                <?php } elseif ($role == '1'){ ?>
                                    <td>
                                        <div class="float-window"></div>
                                        <button class="btn-ubah-antrian" onclick="openNestedFloatWindowOption('<?= $row["ID_Antrian"]; ?>', '<?= $Antrian ?>')">
                                            <i class="fas fa-repeat"></i> Ubah Antrian
                                        </button>
                                        <button class="btn-ubah-status" onclick="openNested2FloatWindowOption('<?= $row["ID_Antrian"]; ?>', '<?= $Antrian ?>')">
                                            <i class="fas fa-repeat"></i> Ubah Status
                                        </button>
                                    </td>
                                <?php } ?>
                            </tr>

                            <?php
                            $timer = $timer + $tambah;
                            $idAntrian = $row["ID_Antrian"] + 1;
                            ?>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <br>
        <?php } ?>
        <?php if ($role == '1') { ?>
            <style>
                .add-link i {
                    font-size: 50px;
                    color: #3C584C;
                }
            </style>

            <a href="antrian/tambahantrian.php?id=<?= $idAntrian; ?>&ruangan=<?= $Antrian; ?>" class="add-link">
                <i class="fa-solid fa-person-circle-plus"></i>
            </a>

        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="script.js"></script>
    
</body>

</html>
