<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$id_resep = $_GET['id'];

$query = "
SELECT resep.*, users.username
FROM resep
JOIN users ON resep.id = users.id
WHERE resep.id_resep = ?
";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_resep);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();


if (isset($_POST['kirim_komentar'])) {

    $komentar = htmlspecialchars($_POST['komentar']);
    $id_user = $_SESSION['id'];

    $insertKomentar = "
    INSERT INTO komentar (id_resep, id, isi_komentar)
    VALUES (?, ?, ?)
    ";

    $stmtKomentar = $koneksi->prepare($insertKomentar);

    $stmtKomentar->bind_param(
        "iis",
        $id_resep,
        $id_user,
        $komentar
    );

    $stmtKomentar->execute();

    header("Location: detail.php?id=$id_resep");
    exit();
}


$queryKomentar = "
SELECT komentar.*, users.username
FROM komentar
JOIN users ON komentar.id = users.id
WHERE komentar.id_resep = ?
ORDER BY komentar.created_at DESC
";

$stmtKomen = $koneksi->prepare($queryKomentar);

$stmtKomen->bind_param("i", $id_resep);

$stmtKomen->execute();

$resultKomentar = $stmtKomen->get_result();
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

    <title>Detail-ResepKita</title>
</head>

<body class="bg-gray-50">

    <!-- NAVBAR -->
    <nav class="flex items-center justify-between px-6 md:px-10 py-4 bg-white shadow">

        <div class="flex items-center gap-3">

            <img
                src="./assets/logo.png"
                alt="Logo"
                class="w-8 h-8 object-contain">

            <span class="text-2xl font-bold text-yellow-950 font-[Playfair Display]">

                Resep Kita

            </span>

        </div>

        <div class="flex items-center gap-4">

            <span class="font-[Fredoka] text-sm">

                Halo,
                <?= htmlspecialchars($_SESSION['username']); ?>

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

            <?= htmlspecialchars($data['judul']); ?>

        </h1>

        <div class="flex items-center gap-2 mt-2 text-gray-500 font-[Fredoka]">

            <img
                src="./assets/profil.png"
                class="w-4 h-4 object-contain">

            <span>
                Oleh
                <span class="font-semibold">
                    <?= htmlspecialchars($data['username']); ?>
                </span>
            </span>

        </div>

    </section>

    <!-- DETAIL RESEP -->
    <section class="mx-10 mb-10 bg-white rounded-lg shadow p-6">

        <!-- FOTO -->
        <img
            src="uploads/<?= htmlspecialchars($data['foto']); ?>"
            class="w-full h-[400px] object-cover rounded-xl mb-8">

        <!-- DESKRIPSI -->
        <div class="mb-8">

            <h2 class="text-2xl font-bold text-yellow-950 mb-3">

                Deskripsi

            </h2>

            <p class="text-gray-700 leading-relaxed font-[Fredoka]">

                <?= nl2br(htmlspecialchars($data['deskripsi'])); ?>

            </p>

        </div>

        <!-- KATEGORI -->
        <div class="mb-8">

            <h2 class="text-2xl font-bold text-yellow-950 mb-3">

                Kategori Daerah

            </h2>

            <span class="bg-yellow-100 text-yellow-950 px-4 py-2 rounded-full font-[Fredoka]">

                <?= htmlspecialchars($data['kategori_daerah']); ?>

            </span>

        </div>

        <!-- BAHAN -->
        <div class="mb-8">

            <h2 class="text-2xl font-bold text-yellow-950 mb-3">

                Bahan-Bahan

            </h2>

            <p class="text-gray-700 leading-relaxed whitespace-pre-line font-[Fredoka]">

                <?= htmlspecialchars($data['bahan']); ?>

            </p>

        </div>

        <!-- LANGKAH -->
        <div class="mb-10">

            <h2 class="text-2xl font-bold text-yellow-950 mb-3">

                Langkah-Langkah

            </h2>

            <p class="text-gray-700 leading-relaxed whitespace-pre-line font-[Fredoka]">

                <?= htmlspecialchars($data['langkah']); ?>

            </p>

        </div>

        <!-- FORM KOMENTAR -->
        <div class="mb-10">

            <h2 class="text-2xl font-bold text-yellow-950 mb-4">

                Tulis Komentar

            </h2>

            <form method="POST" class="flex flex-col gap-4">

                <textarea
                    name="komentar"
                    rows="4"
                    required
                    placeholder="Tulis komentar..."
                    class="w-full border p-4 rounded-lg"></textarea>

                <button
                    type="submit"
                    name="kirim_komentar"
                    class="bg-yellow-950 hover:bg-yellow-800 text-white px-6 py-3 rounded w-fit">

                    Kirim Komentar

                </button>

                <div class="flex items-center gap-3">

                <a href="index.php"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Kembali
                </a>

            </div>

            </form>

        </div>

        <!-- LIST KOMENTAR -->
        <div>

            <h2 class="text-2xl font-bold text-yellow-950 mb-5">

                Komentar Pengguna

            </h2>

            <div class="flex flex-col gap-4">

                <?php while ($komen = mysqli_fetch_assoc($resultKomentar)) : ?>

                    <div class="bg-gray-100 rounded-xl p-4">

                        <div class="flex items-center gap-2 mb-2">

                            <img
                                src="./assets/profil.png"
                                class="w-4 h-4 object-contain">

                            <span class="font-semibold font-[Fredoka]">

                                <?= htmlspecialchars($komen['username']); ?>

                            </span>

                        </div>

                        <p class="text-gray-700 font-[Fredoka] leading-relaxed">

                            <?= nl2br(htmlspecialchars($komen['isi_komentar'])); ?>

                        </p>

                    </div>

                <?php endwhile; ?>

            </div>

        </div>

    </section>

</body>

</html>