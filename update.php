<?php
session_start();
include 'koneksi.php'; 

if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
}

$id_resep = $_GET['id']; 

// Ambil data resep
$query = "SELECT * FROM resep WHERE id_resep = ?"; 
$stmt = $koneksi->prepare($query); 
$stmt->bind_param("i", $id_resep); 
$stmt->execute(); 

$result = $stmt->get_result(); 
$data = $result->fetch_assoc(); 

// JIKA DATA TIDAK ADA ATAU (ROLE-NYA BUKAN ADMIN DAN BUKAN PEMILIK RESEP)
if (!$data || ($_SESSION['ROLE'] != 'admin' && $data['id'] != $_SESSION['id'])) {
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location='index.php';</script>";
    exit();
}

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

<body class=" bg-center bg-repeat bg-[length:300px_auto]" style="background-image: url('assets/navbg.jpg')">

    <nav
        class="flex justify-between  z-100 w-full mx-auto rounded- shadow-xl py-5 px-8 border-b border-neutral-900 bg-repeat bg-[length:300px_auto]"
    style="background-image: url('assets/navbg.jpg')">
        <img src="assets/mega1.png" alt="" class="absolute right-20 -top-6 w-17 h-12 scale-150 z-100">
        <img src="assets/mega1.png" alt="" class="absolute left-40 top-11 w-17 h-12 scale-150 z-100">
        <img src="assets/mega3.png" alt="" class="absolute -left-3 -top-3 w-17 h-12 scale-160 z-100">
        <img src="assets/mega2.png" alt="" class="absolute -right-0 top-10 w-17 h-12 scale-130 z-100">

        <div>
            <a href="index.php"
                class="font-bold font-[fredoka] text-3xl text-yellow-950">

                Resep Kita

            </a>
        </div>

        <div class="flex items-center gap-7  ">

            <span class="font-[Fredoka] text-sm text-yellow-950">
                Halo,
                <?= htmlspecialchars($_SESSION['username']); ?>
            </span>

            <a href="logout.php"
                class="bg-orange-900 text-white px-4 py-2 rounded-full hover:scale-110 hover:bg-yellow-900 font-[Merryweather] duration-300">

                Logout

            </a>



        </div>

    </nav>

    <!-- JUDUL -->
    <section class=" mt-5 px-10 py-6 bg-[#EFE6D5] mb-4 border-2 border-[#c5b598] ">

        <h1 class="text-3xl font-bold text-yellow-950 font-[Playfair Display]">
            Update Resep
        </h1>

        <p class="text-gray-500 mt-2 font-[Fredoka]">
            Perbarui resep anda disini.
        </p>

    </section>

    <!-- FORM -->
    <section class="mx-10 mb-10 bg-[#EFE6D5] shadow-2xl rounded-2xl border-2 border-[#c5b598] p-6">

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
                    class="w-full border border-[#c5b598] bg-[#F5EFE4] rounded">

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
                    class="w-full border border-[#c5b598] bg-[#F5EFE4] rounded p-2">

            </div>

            <!-- DESKRIPSI -->
            <div>

                <label class="block mb-2 font-semibold text-yellow-950">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    rows="3"
                    placeholder="Ceritakan tentang resep anda..."
                    class="w-full border border-[#c5b598] bg-[#F5EFE4] rounded p-2"><?= htmlspecialchars($data['deskripsi']); ?></textarea>

            </div>

            <!-- KATEGORI -->
            <div>

                <label class="block mb-2 font-semibold text-yellow-950">
                    Kategori Daerah
                </label>

                <select
                    name="kategori_daerah"
                    class="w-full border border-[#c5b598] bg-[#F5EFE4] rounded p-2">

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
                    class="w-full border border-[#c5b598] bg-[#F5EFE4] rounded p-2"><?= htmlspecialchars($data['bahan']); ?></textarea>

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
                    class="w-full border border-[#c5b598] bg-[#F5EFE4] rounded p-2"><?= htmlspecialchars($data['langkah']); ?></textarea>

            </div>

            <!-- BUTTON -->
            <div class="flex items-center gap-3">

                <a href="index.php"
                    class="bg-red-700 text-white px-4 py-2 rounded-full hover:scale-110 hover:bg-red-900 font-[Merryweather] duration-300">
                    Batal
                </a>

                <button
                    type="submit"
                    name="update"
                    class="bg-orange-900 text-white px-4 py-2 rounded-full hover:scale-110 hover:bg-yellow-900 font-[Merryweather] duration-300">

                    Update Resep

                </button>

            </div>

        </form>

    </section>

</body>

</html>