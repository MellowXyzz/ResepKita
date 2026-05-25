<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


if (isset($_POST['simpan'])) {


    $id = $_SESSION['id'];
    $judul = htmlspecialchars($_POST['judul']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $kategori = $_POST['kategori_daerah'];
    $bahan = htmlspecialchars($_POST['bahan']);
    $langkah = htmlspecialchars($_POST['langkah']);


    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];


    $folder = "uploads/";


    $namaFoto = time() . "_" . $foto;


    if (move_uploaded_file($tmp, $folder . $namaFoto)) {


        $query = "INSERT INTO resep 
        (id, judul, deskripsi, kategori_daerah, foto, bahan, langkah)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $koneksi->prepare($query);

        $stmt->bind_param(
            "issssss",
            $id,
            $judul,
            $deskripsi,
            $kategori,
            $namaFoto,
            $bahan,
            $langkah
        );

        if ($stmt->execute()) {

            echo "
            <script>
                alert('Resep berhasil ditambahkan!');
                window.location='index.php';
            </script>
            ";
        } else {
            echo "Gagal menambahkan resep!";
        }

        $stmt->close();
    } else {
        echo "Upload foto gagal!";
    }
}
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

    <title>Create Resep</title>
</head>

<body class="bg-gray-50">


  <nav
    class="flex justify-between z-100 w-full shadow-xl rounded-xl py-5 px-8 bg-cover bg-center border- border-neutral-900"
    style="background-image: url('./assets/navbg.jpg')">
    <img src="assets/mega1.png" alt="" class="absolute right-27 -top-6 w-17 h-12 scale-150 z-100">
    <img src="assets/mega1.png" alt="" class="absolute left-37 top-11 w-17 h-12 scale-150 z-100">
    <img src="assets/mega3.png" alt="" class="absolute -left-3 -top-6 w-17 h-12 scale-160 z-100">
    <img src="assets/mega2.png" alt="" class="absolute right-5 top-15 w-17 h-12 scale-130 z-100">
  
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
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full transition">

            Logout

          </a>



    </div>

  </nav>

    <div class="pt-9"></div>

    <section class="px-10 py-6">

        <h1 class="text-3xl font-bold text-yellow-950 font-[Playfair-Display]">
            Tambah Resep Baru
        </h1>

        <p class="text-gray-500 mt-2 font-[Fredoka]">
            Bagikan resep andalanmu ke semua orang.
        </p>

    </section>


    <section class="mx-10 mb-10 bg-white rounded-lg shadow p-6">

        <form action="" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">


            <div>
                <label class="block mb-2 font-[Fredoka] font-semibold text-yellow-950">
                    Foto Masakan
                </label>

                <input
                    type="file"
                    name="foto"
                    accept="image/*"
                    required
                    class="w-full border p-2 rounded">
            </div>


            <div>
                <label class="block mb-2 font-[Fredoka] font-semibold text-yellow-950">
                    Judul Resep
                </label>

                <input
                    type="text"
                    name="judul"
                    required
                    placeholder="Contoh : Nasi Goreng Jawa"
                    class="w-full border p-2 rounded">
            </div>


            <div>
                <label class="block mb-2 font-semibold font-[Fredoka] text-yellow-950">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    rows="3"
                    placeholder="Ceritakan tentang resep anda..."
                    class="w-full border p-2 rounded"></textarea>
            </div>


            <div>
                <label class="block mb-2 font-semibold font-[Fredoka] text-yellow-950">
                    Kategori Daerah
                </label>

                <select
                    name="kategori_daerah"
                    required
                    class="w-full border p-2 rounded">

                    <option value="">Pilih kategori</option>
                    <option value="jawa">Jawa</option>
                    <option value="kaltim">Kalimantan Timur</option>
                    <option value="sumatra">Sumatra</option>
                    <option value="papua">Papua</option>
                    <option value="lainnya">Lainnya</option>

                </select>
            </div>


            <div>
                <label class="block mb-2 font-[Fredoka] font-semibold text-yellow-950">
                    Bahan-Bahan
                </label>

                <textarea
                    name="bahan"
                    required
                    rows="5"
                    placeholder="Contoh : Bawang putih, garam, ayam..."
                    class="w-full border p-2 rounded"></textarea>
            </div>


            <div>
                <label class="block mb-2 font-[Fredoka] font-semibold text-yellow-950">
                    Langkah-Langkah
                </label>

                <textarea
                    name="langkah"
                    required
                    rows="5"
                    placeholder="Contoh : Panaskan minyak..."
                    class="w-full border p-2 rounded"></textarea>
            </div>


            <div class="flex items-center font-[Fredoka] gap-3">

                <a href="index.php"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Batal
                </a>

                <button
                    type="submit"
                    name="simpan"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

                    Simpan Resep

                </button>

            </div>

        </form>

    </section>

</body>

</html>