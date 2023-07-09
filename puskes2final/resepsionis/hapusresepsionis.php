<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "antri");

$ID_Resepsionis = $_GET['id'];

if ($ID_Resepsionis === '1') {
    echo "
        <script>
            alert('Data dengan ID 1 tidak dapat dihapus.');
            document.location.href = 'tampilresepsionis.php';
        </script>
    ";
} else {
    // data 1 tidak boleh dihapus
    $querycek = "SELECT COUNT(*) AS total FROM Resepsionis;";
    $check = mysqli_query($conn, $querycek);
    $row = mysqli_fetch_assoc($check);
    $totalRecords = $row["total"];

    if ($totalRecords <= 1) {
        echo "
            <script>
                alert('Data resepsionis minimal 1');
                document.location.href = 'tampilresepsionis.php';
            </script>
        ";
    } else {
        $query1 = "DELETE FROM Antrian WHERE ID_Resepsionis = $ID_Resepsionis";
        $query = "DELETE FROM Resepsionis WHERE ID_Resepsionis = $ID_Resepsionis";

        mysqli_query($conn, $query1);
        mysqli_query($conn, $query);

        if (mysqli_affected_rows($conn) > 0) {
            echo "
                <script>
                    alert('Data resepsionis berhasil dihapus');
                    document.location.href = 'tampilresepsionis.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Data resepsionis gagal dihapus');
                    document.location.href = 'tampilresepsionis.php';
                </script>
            ";
        }
    }
}

?>
