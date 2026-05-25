<?php
include 'auth.php';
include 'koneksi.php';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// Membuat pola pencarian SQL (contoh: %soto%)
$search_pattern = "%" . $keyword . "%";

// Query SQL dengan Prepared Statement (Menggunakan database driver mysqli_stmt)
$query = "
SELECT DISTINCT resep.*, users.username
FROM resep
JOIN users ON resep.id = users.id
LEFT JOIN komentar ON resep.id_resep = komentar.id_resep
WHERE 
    resep.judul LIKE ? 
    OR resep.kategori_daerah LIKE ? 
    OR komentar.isi_komentar LIKE ?
ORDER BY resep.created_at DESC
";

$stmt = $koneksi->prepare($query);
// Bind parameter string ("sss" karena ada 3 tanda tanya)
$stmt->bind_param("sss", $search_pattern, $search_pattern, $search_pattern);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arapey:ital@0;1&family=Fredoka:wght@300..700&family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&family=Instrument+Serif:ital@0;1&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <title>Cari-ResepKita</title>
</head>

<body class="bg-gray-50">

    <nav
      class="flex justify-between items-center z-100 w-full shadow-xl py-5 px-8 bg-cover bg-center border- border-neutral-900"
      style="background-image: url('./assets/navbg.jpg')">
      
      <img src="assets/mega1.png" alt="" class="absolute right-27 -top-6 w-17 h-12 scale-150 z-100">
      <img src="assets/mega1.png" alt="" class="absolute left-37 top-11 w-17 h-12 scale-150 z-100">
      <img src="assets/mega3.png" alt="" class="absolute -left-3 -top-6 w-17 h-12 scale-160 z-100">
      <img src="assets/mega2.png" alt="" class="absolute right-5 top-15 w-17 h-12 scale-130 z-100">

      <div>
        <a href="index.php" class="font-bold font-[fredoka] text-3xl text-yellow-950">
          Resep Kita
        </a>
      </div>

      <div class="flex items-center gap-7 ml-auto">
          
          <form action="search.php" method="GET" class="w-80 relative">
              <input
                type="text"
                name="keyword"
                value="<?= htmlspecialchars($keyword); ?>"
                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md pl-3 pr-20 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow"
                placeholder="Jawa, Pecel, Nikmat..." />
              
              <button
                type="submit"
                class="absolute top-1 right-1 flex items-center rounded bg-slate-800 py-1 px-2.5 border border-transparent text-center text-sm text-white transition-all shadow-sm hover:shadow focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none cursor-pointer"
              >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
                    <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                </svg> Cari
              </button>
          </form>

          <span class="font-[Fredoka] text-sm text-yellow-950 whitespace-nowrap">
            Halo, <?= htmlspecialchars($_SESSION['username']); ?>
          </span>

          <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full transition whitespace-nowrap">
            Logout
          </a>

      </div>
    </nav>

    <div class="pt-9"></div>

    <section class="px-10 py-6">
        <h1 class="text-3xl font-bold text-yellow-950 font-[Playfair-Display]">
            Cari Resep
        </h1>
        <p class="text-gray-500 mt-2 font-[Fredoka]">
            Cari resep yang sesuai dengan keinginan anda.
        </p>
    </section>

    <section class="mx-10 mb-10 bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-[playfair-display] font-bold mb-8 text-yellow-950">
            <?= ($keyword != '') ? "Hasil Pencarian: '" . htmlspecialchars($keyword) . "'" : "Hasil Pencarian"; ?>
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($data = mysqli_fetch_assoc($result)) : ?>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition flex flex-col justify-between">
                        <div>
                            <img
                                src="uploads/<?= $data['foto']; ?>"
                                alt="<?= $data['judul']; ?>"
                                class="w-full h-52 object-cover">

                            <div class="p-5">
                                <h2 class="text-xl font-[fredoka] font-bold text-yellow-950 mb-2">
                                    <?= htmlspecialchars($data['judul']); ?>
                                </h2>

                                <div class="flex items-center gap-2 text-gray-500 font-[fredoka] text-sm mb-4">
                                    <span>Oleh :</span>
                                    <img
                                        src="./assets/profil.png"
                                        alt="Profil"
                                        class="w-4 h-4 object-contain">
                                    <span class="font-semibold">
                                        <?= htmlspecialchars($data['username']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 pt-0 flex flex-wrap gap-2">
                            <a href="detail.php?id=<?= $data['id_resep']; ?>"
                                class="inline-block font-[fredoka] bg-yellow-950 hover:bg-yellow-800 text-white px-3 py-2 rounded text-sm transition">
                                Lihat Resep
                            </a>

                            <a href="update.php?id=<?= $data['id_resep']; ?>"
                                class="inline-block font-[fredoka] bg-yellow-950 hover:bg-yellow-800 text-white px-3 py-2 rounded text-sm transition">
                                Edit
                            </a>

                            <a href="delete.php?id=<?= $data['id_resep']; ?>"
                                class="inline-block font-[fredoka] bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm transition"
                                onclick="return confirm('Hapus resep ini?')">
                                Hapus
                            </a>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
                    <p class="text-gray-500 font-[fredoka] text-lg">Maaf, resep dengan kata kunci tersebut tidak ditemukan.</p>
                </div>
            <?php endif; ?>

        </div>

        <div class="flex items-center mt-4 font-[Fredoka] gap-3">

                <a href="index.php"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Kembali
                </a>

            </div>
    </section>

    

</body>
</html>