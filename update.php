<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$id_resep = $_GET['id'];


$query = "SELECT * FROM resep WHERE id_resep = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_resep);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();


if (isset($_POST['update'])) {

    $judul = htmlspecialchars($_POST['judul']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $kategori = $_POST['kategori_daerah'];
    $bahan = htmlspecialchars($_POST['bahan']);
    $langkah = htmlspecialchars($_POST['langkah']);

    
    if ($_FILES['foto']['name'] != "") {

        $foto = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];

        $folder = "uploads/";
        $namaFoto = time() . "_" . $foto;

        move_uploaded_file($tmp, $folder . $namaFoto);

        
        $update = "UPDATE resep 
        SET judul=?, deskripsi=?, kategori_daerah=?, foto=?, bahan=?, langkah=?
        WHERE id_resep=?";

        $stmtUpdate = $koneksi->prepare($update);

        $stmtUpdate->bind_param(
            "ssssssi",
            $judul,
            $deskripsi,
            $kategori,
            $namaFoto,
            $bahan,
            $langkah,
            $id_resep
        );

    } else {

        
        $update = "UPDATE resep 
        SET judul=?, deskripsi=?, kategori_daerah=?, bahan=?, langkah=?
        WHERE id_resep=?";

        $stmtUpdate = $koneksi->prepare($update);

        $stmtUpdate->bind_param(
            "sssssi",
            $judul,
            $deskripsi,
            $kategori,
            $bahan,
            $langkah,
            $id_resep
        );
    }

    
    if ($stmtUpdate->execute()) {

        echo "
        <script>
            alert('Resep berhasil diperbarui!');
            window.location='index.php';
        </script>
        ";

    } else {

        echo "Gagal update resep!";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&family=Playfair+Display:wght@400..900&display=swap" rel="stylesheet">

    <title>Update Resep</title>
</head>

<body class="bg-gray-50">

    <!-- NAVBAR -->
    <nav class="flex items-center justify-between px-6 md:px-10 py-4 bg-white shadow">

        <div class="flex items-center gap-3">
            <img src="./assets/logo.png" alt="Logo" class="w-8 h-8 object-contain">

            <span class="text-2xl font-bold text-yellow-950 font-[Playfair Display]">
                Resep Kita
            </span>
        </div>

        <div class="flex items-center gap-4">

            <span class="font-[Fredoka] text-sm">
                Halo, <?= htmlspecialchars($_SESSION['username']); ?>
            </span>

            <a href="logout.php"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                Logout
            </a>

        </div>

    </nav>

    <!-- JUDUL -->
    <section class="px-10 py-6">

        <h1 class="text-3xl font-bold text-yellow-950 font-[Playfair Display]">
            Update Resep
        </h1>

        <p class="text-gray-500 mt-2 font-[Fredoka]">
            Perbarui resep anda disini.
        </p>

    </section>

    <!-- FORM -->
    <section class="mx-10 mb-10 bg-white rounded-lg shadow p-6">

        <form action="" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">

            <!-- FOTO -->
            <div>

                <label class="block mb-2 font-semibold text-yellow-950">
                    Foto Masakan
                </label>

                <img
                    src="uploads/<?= $data['foto']; ?>"
                    class="w-40 rounded mb-3">

                <input
                    type="file"
                    name="foto"
                    accept="image/*"
                    class="w-full border p-2 rounded">

            </div>

            <!-- JUDUL -->
            <div>

                <label class="block mb-2 font-semibold text-yellow-950">
                    Judul Resep
                </label>

                <input
                    type="text"
                    name="judul"
                    required
                    value="<?= htmlspecialchars($data['judul']); ?>"
                    class="w-full border p-2 rounded">

            </div>

            <!-- DESKRIPSI -->
            <div>

                <label class="block mb-2 font-semibold text-yellow-950">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    rows="3"
                    class="w-full border p-2 rounded"><?= htmlspecialchars($data['deskripsi']); ?></textarea>

            </div>

            <!-- KATEGORI -->
            <div>

                <label class="block mb-2 font-semibold text-yellow-950">
                    Kategori Daerah
                </label>

                <select
                    name="kategori_daerah"
                    class="w-full border p-2 rounded">

                    <option value="jawa" <?= ($data['kategori_daerah'] == 'jawa') ? 'selected' : ''; ?>>
                        Jawa
                    </option>

                    <option value="kaltim" <?= ($data['kategori_daerah'] == 'kaltim') ? 'selected' : ''; ?>>
                        Kalimantan Timur
                    </option>

                    <option value="sumatra" <?= ($data['kategori_daerah'] == 'sumatra') ? 'selected' : ''; ?>>
                        Sumatra
                    </option>

                    <option value="papua" <?= ($data['kategori_daerah'] == 'papua') ? 'selected' : ''; ?>>
                        Papua
                    </option>

                    <option value="lainnya" <?= ($data['kategori_daerah'] == 'lainnya') ? 'selected' : ''; ?>>
                        Lainnya
                    </option>

                </select>

            </div>

            <!-- BAHAN -->
            <div>

                <label class="block mb-2 font-semibold text-yellow-950">
                    Bahan-Bahan
                </label>

                <textarea
                    name="bahan"
                    rows="5"
                    required
                    class="w-full border p-2 rounded"><?= htmlspecialchars($data['bahan']); ?></textarea>

            </div>

            <!-- LANGKAH -->
            <div>

                <label class="block mb-2 font-semibold text-yellow-950">
                    Langkah-Langkah
                </label>

                <textarea
                    name="langkah"
                    rows="5"
                    required
                    class="w-full border p-2 rounded"><?= htmlspecialchars($data['langkah']); ?></textarea>

            </div>

            <!-- BUTTON -->
            <div class="flex items-center gap-3">

                <a href="index.php"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Batal
                </a>

                <button
                    type="submit"
                    name="update"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

                    Update Resep

                </button>

            </div>

        </form>

    </section>

</body>

</html>