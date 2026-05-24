<?php
include 'auth.php';
include 'koneksi.php';

$query = "
SELECT resep.*, users.username
FROM resep
JOIN users ON resep.id = users.id
ORDER BY resep.created_at DESC
";

$result = mysqli_query($koneksi, $query);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Index-ResepKita</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
    rel="stylesheet" />
</head>

<body class=" bg-no-repeat bg-cover bg-fixed" style="background-image: url(&quot;./assets/backgroundindex.jpg&quot;)">
  <nav
    class="flex justify-between fixed top-4 left-0 right-0 z-100 w-[99%] mx-auto rounded-xl shadow-xl py-5 px-8 bg-cover bg-center border- border-neutral-900"
    style="background-image: url('./assets/navbg.jpg')">
    <img src="assets/mega1.png" alt="" class="absolute right-44 -top-9 w-21 h-15 scale-150 z-100">
    <img src="assets/mega1.png" alt="" class="absolute left-44 top-13 w-21 h-15 scale-150 z-100">
    <img src="assets/mega3.png" alt="" class="absolute -left-2 -top-12 w-21 h-15 scale-160 z-100">
    <img src="assets/mega2.png" alt="" class="absolute -right-8 top-15 w-21 h-15 scale-130 z-100">
  
 <div>
        <a href="index.php"
          class="font-bold font-[fredoka] text-3xl text-yellow-950">

          Resep Kita

        </a>
      </div>

    <div class="flex items-center gap-7  ">

        <a href="index.php"
          class="font-bold font-[Fredoka] text-yellow-950 hover:text-yellow-700 transition">
          Beranda
        </a>

        <a href="kategori.php"
          class="font-bold font-[Fredoka] text-yellow-950 hover:text-yellow-700 transition">
          Kategori
        </a>


        <a href="create.php"
          class="font-bold font-[Fredoka] text-yellow-950 hover:text-yellow-700 transition">
          Tambah Resep
        </a>

        <a href="search.php"
          class="font-bold font-[Fredoka] text-yellow-950 hover:text-yellow-700 transition">
          Cari Resep
        </a>
          <span class="font-[Fredoka] text-sm text-yellow-950">
            Halo,
            <?= htmlspecialchars($_SESSION['username']); ?>
          </span>

          <a href="logout.php"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full transition">

            Logout

          </a>



    </div>

  </nav>

<!-- Transparant -->
  <div class="h-screen">

  </div>
  <!-- POSTER istilahnya -->

  <div class="relative h-screen  top-4 shadow-xl mx-auto w-full"
    style="background-image: url(&quot;./assets/navbg.jpg&quot;)" ;>
    <img src="assets/pembatas.png" alt="" class="absolute left-80 top-6 w-100 h-80 scale-500">
    <img src="assets/wayang.png" alt="" class=" absolute -left-9 top-3 w-21 h-32 scale-340 rotate-7">
    <img src="assets/pembatas.png" alt="" class="absolute left-80 -bottom-100 w-100 h-80 scale-500">
    <section class="w-full min-h-screen flex items-center justify-center px-10 gap-90">
      <div class="max-w-7xl w-full flex items-center">
        <div class="w-[35%] relative">
          <h1 class="text-[80px] font-black font-[playfair-display] uppercase text-black">
            Nikmati <br />
            Cita Rasa <br />
            Nusantara
          </h1>
          <p class="mt-8 text-xl text-black font-[fredoka] leading-relaxed">
            Temukan berbagai resep masakan Indonesia yang lezat, mudah dibuat,
            dan cocok untuk hidangan keluarga setiap hari.
          </p>

          <!-- BUTTON -->
          <button class="mt-10 bg-black text-white px-10 py-4 rounded-full text-lg hover:scale-105 duration-300">
            LIHAT RESEP
          </button>
        </div>

        <div class="w-[65%] h-[550px] overflow-hidden rounded-[40px] ml-20">
          <img src="./assets/backgroundindex.jpg" alt="Makanan Indonesia" class="w-full h-full object-cover" />
        </div>
      </div>
    </section>


  <section class="rounded-xl h-screen mt-20 top-4 shadow-xl mx-auto px-10"
    style="background-image: url(&quot;./assets/navbg.jpg&quot;)" ;>

    <h1 class="text-3xl font-[playfair-dislay] font-bold mb-8 text-yellow-950">
      Resep Terbaru
    </h1>

    <!-- GRID CARD -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

      <?php while ($data = mysqli_fetch_assoc($result)) : ?>

        <!-- CARD -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">

          <!-- GAMBAR -->
          <img
            src="uploads/<?= $data['foto']; ?>"
            alt="<?= $data['judul']; ?>"
            class="w-full h-52 object-cover">

          <!-- ISI CARD -->
          <div class="p-5">

            <!-- JUDUL -->
            <h2 class="text-xl font-[fredoka] font-bold text-yellow-950 mb-2">
              <?= htmlspecialchars($data['judul']); ?> 
            </h2>

            <!-- USER -->
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

            <!-- BUTTON -->
            <a href="detail.php?id=<?= $data['id_resep']; ?>"
              class="inline-block font-[fredoka] bg-yellow-950 hover:bg-yellow-800 text-white px-4 py-2 rounded">

              Lihat Resep

            </a>

            <a href="update.php?id=<?= $data['id_resep']; ?>"
              class="inline-block font-[fredoka] bg-yellow-950 hover:bg-yellow-800 text-white px-4 py-2 rounded">

              Edit

            </a>

            <a href="delete.php?id=<?= $data['id_resep']; ?>"
              class="inline-block font-[fredoka] bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded"
              onclick="return confirm('Hapus resep ini?')">

              Hapus

            </a>

          </div>

        </div>

      <?php endwhile; ?>

    </div>

  </section>
    </div>
</body>

</html>