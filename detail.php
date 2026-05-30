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

<body class="bg-center bg-repeat bg-[length:300px_auto]" style="background-image: url('assets/navbg.jpg')">

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
    <section class="mx-10 mb-10 bg-[#EFE6D5] shadow-2xl rounded-2xl border-2 border-[#c5b598] p-6">

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
                    class="w-full border border-[#c5b598] bg-[#F5EFE4] rounded p-2"></textarea>

                <button
                    type="submit"
                    name="kirim_komentar"
                    class="bg-orange-900 text-white py-5 px-5 rounded hover:scale-110 hover:bg-yellow-900 text-lg font-[Merryweather] duration-300 w-fit">

                    Kirim Komentar

                </button>

                <div class="flex items-center gap-3">

                <a href="index.php"
                    class="bg-red-700 text-white px-4 py-2 rounded hover:scale-110 hover:bg-red-900 font-[Merryweather] duration-300">
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

            <div class="flex flex-col gap-4 ">

                <?php while ($komen = mysqli_fetch_assoc($resultKomentar)) : ?>

                    <div class="border border-[#c5b598] bg-[#F5EFE4] rounded p-5">

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