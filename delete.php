<?php
session_start();
include 'koneksi.php';


if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}


if (isset($_GET['id'])) {

    $id_resep = $_GET['id'];

    
    $queryFoto = "SELECT foto FROM resep WHERE id_resep = ?";
    $stmtFoto = $koneksi->prepare($queryFoto);

    $stmtFoto->bind_param("i", $id_resep);
    $stmtFoto->execute();

    $resultFoto = $stmtFoto->get_result();
    $dataFoto = $resultFoto->fetch_assoc();

    
    if ($dataFoto) {

        $path = "uploads/" . $dataFoto['foto'];

        if (file_exists($path)) {
            unlink($path);
        }
    }

    
    $query = "DELETE FROM resep WHERE id_resep = ?";

    $stmt = $koneksi->prepare($query);

    $stmt->bind_param("i", $id_resep);

    if ($stmt->execute()) {

        echo "
        <script>
            alert('Resep berhasil dihapus!');
            window.location='index.php';
        </script>
        ";

    } else {

        echo "Gagal menghapus resep!";

    }
}
?>