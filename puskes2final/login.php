<?php

session_start();

if (isset($_SESSION['login'])) {
    header("Location: index1.php");
    exit;
} elseif (isset($_SESSION['loginDokter'])) {
    header("Location: index1.php");
    exit;
} elseif (isset($_SESSION['loginPasien'])) {
    header("Location: index1.php");
    exit;
}

// Cek apakah form login telah disubmit
if (isset($_POST['login'])) {
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    // Koneksi ke database
    $conn = mysqli_connect("localhost", "root", "", "antri");

    // Validasi login
    $query = "SELECT * FROM Resepsionis WHERE Nama_R = '$nama' AND ID_Resepsionis = '$password'";
    $result = mysqli_query($conn, $query);

    $query2 = "SELECT * FROM Dokter WHERE Nama_D = '$nama' AND ID_Dokter = '$password'";
    $result2 = mysqli_query($conn, $query2);

    $query3 = "SELECT * FROM Pasien WHERE Nama_P = '$nama' AND ID_Pasien = '$password'";
    $result3 = mysqli_query($conn, $query3);

    if (mysqli_num_rows($result) === 1) {

        $_SESSION['login'] = $password;

        // Redirect ke halaman utama
        header("Location: index1.php");
        exit;
    } elseif (mysqli_num_rows($result2) === 1) {

        $_SESSION['loginDokter'] = true;

        // Redirect ke halaman utama
        header("Location: index1.php");
        exit;
    } elseif (mysqli_num_rows($result3) === 1) {

        $_SESSION['loginPasien'] = true;

        // Redirect ke halaman utama
        header("Location: index1.php");
        exit;
    } else {
        // Jika login tidak valid, tampilkan pesan error
        $error = "Nama pengguna atau kata sandi salah!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<title>Halaman Login</title>
</head>

<body>
    <div class="scroll-down">
        SCROLL KEBAWAH UNTUK LOGIN
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
            <path d="M16 3C8.832031 3 3 8.832031 3 16s5.832031 13 13 13 13-5.832031 13-13S23.167969 3 16 3zm0 2c6.085938 0 11 4.914063 11 11 0 6.085938-4.914062 11-11 11-6.085937 0-11-4.914062-11-11C5 9.914063 9.914063 5 16 5zm-1 4v10.28125l-4-4-1.40625 1.4375L16 23.125l6.40625-6.40625L21 15.28125l-4 4V9z" />
        </svg>
    </div>
    <div class="container">
    <div class="modal">
        <div class="modal-container">
            <div class="modal-left">
                <h1 class="modal-title">SELAMAT DATANG</h1>
                <p class="modal-desc"> Silahkan login dan nikmati kemudahan layanan kesehatan tanpa hambatan</p>
                <form action="" method="post">
                    <div class="input-block">
                        <label for="nama" class="input-label">Username</label>
                        <input type="text" name="nama" id="nama" placeholder="masukan username anda" required>
                    </div>
                    <div class="input-block">
                        <label for="password" class="input-label">Password</label>
                        <input type="password" name="password" id="password" placeholder="masukan password" required>
                    </div>
                    <div class="modal-buttons">
                        <button class="input-button" type="submit" name="login">Login</button>
                    </div>
                </form>
                <?php if (isset($error)) { ?>
                    <p><?php echo $error; ?></p>
                <?php } ?>
            </div>
            <div class="modal-right">
                <a href='https://www.linkpicture.com/view.php?img=LPic649ed683723ad788419856'>
                    <img src='https://www.linkpicture.com/q/Antrian-RPL-1.png' type='image'></a>
            </div>
            <button class="icon-button close-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                    <path d="M 25 3 C 12.86158 3 3 12.86158 3 25 C 3 37.13842 12.86158 47 25 47 C 37.13842 47 47 37.13842 47 25 C 47 12.86158 37.13842 3 25 3 z M 25 5 C 36.05754 5 45 13.94246 45 25 C 45 36.05754 36.05754 45 25 45 C 13.94246 45 5 36.05754 5 25 C 5 13.94246 13.94246 5 25 5 z M 16.990234 15.990234 A 1.0001 1.0001 0 0 0 16.292969 17.707031 L 23.585938 25 L 16.292969 32.292969 A 1.0001 1.0001 0 1 0 17.707031 33.707031 L 25 26.414062 L 32.292969 33.707031 A 1.0001 1.0001 0 1 0 33.707031 32.292969 L 26.414062 25 L 33.707031 17.707031 A 1.0001 1.0001 0 0 0 32.980469 15.990234 A 1.0001 1.0001 0 0 0 32.292969 16.292969 L 25 23.585938 L 17.707031 16.292969 A 1.0001 1.0001 0 0 0 16.990234 15.990234 z"></path>
                </svg>
            </button>
        </div>
        <button class="modal-button">Klik disini untuk login</button>
    </div>
    </div>
</body>

</html>
<script>
    const body = document.querySelector("body");
    const modal = document.querySelector(".modal");
    const modalButton = document.querySelector(".modal-button");
    const closeButton = document.querySelector(".close-button");
    const scrollDown = document.querySelector(".scroll-down");
    let isOpened = false;

    const openModal = () => {
        modal.classList.add("is-open");
        body.style.overflow = "hidden";
    };

    const closeModal = () => {
        modal.classList.remove("is-open");
        body.style.overflow = "initial";
        document.location.href = 'login.php';
    };

    window.addEventListener("scroll", () => {
        if (window.scrollY > window.innerHeight / 3 && !isOpened) {
            isOpened = true;
            scrollDown.style.display = "none";
            openModal();
        }
    });

    modalButton.addEventListener("click", openModal);
    closeButton.addEventListener("click", closeModal);

    document.onkeydown = evt => {
        evt = evt || window.event;
        evt.keyCode === 27 ? closeModal() : false;
    };
</script>