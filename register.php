<?php
include 'koneksi.php';

$pesan = "";
if (isset($_POST['register'])) {
    // Mengambil data dari input HTML ('name' dan 'pass')
    $username = $_POST['name'];
    $password = $_POST['pass'];
    $role = 'user'; // Nilai default untuk role baru

    if (!empty($username) && !empty($password)) {
        // 1. Cek apakah username sudah terdaftar menggunakan Prepared Statement
        $stmt_cek = $koneksi->prepare("SELECT username FROM users WHERE username = ?");
        $stmt_cek->bind_param("s", $username);
        $stmt_cek->execute();
        $result_cek = $stmt_cek->get_result();

        if ($result_cek->num_rows > 0) {
            $pesan = "<p class='text-red-500 font-[fredoka] mb-4'>Username sudah digunakan, silakan pilih yang lain.</p>";
        } else {
            // 2. Enkripsi password demi keamanan jika username belum ada yang pakai
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 3. Gunakan Prepared Statement untuk proses Insert data
            $stmt = $koneksi->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt->execute()) {
                $pesan = "<script>alert('Registrasi berhasil! Silahkan login.'); window.location='login.php';</script>";
            } else {
                $pesan = "<p class='text-red-500 font-[fredoka] mb-4'>Terjadi kesalahan saat registrasi.</p>";
            }
        }
    } else {
        $pesan = "<p class='text-red-500 font-[fredoka] mb-4'>Semua kolom harus diisi!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arapey:ital@0;1&family=Fredoka:wght@300..700&family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&family=Instrument+Serif:ital@0;1&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <title>Register-ResepKita</title>
</head>
<body class="bg-cover bg-center bg-no-repeat" style="background-image: url('./assets/background2.jpg');">

    <div class="flex items-center justify-center min-h-screen backdrop-blur-sm">
        <div class="relative flex flex-col m-6 space-y-8 bg-white shadow-2xl rounded-2xl md:flex-row md:space-y-0">

            <form action="" method="POST" class="flex flex-col justify-center p-8 md:p-14">
                
                <div class="flex items-center gap-3 mb-3">
                    <img src="./assets/logo.png" alt="Logo" class="w-12 h-12 object-contain" />
                    <span class="text-4xl font-[arapey] text-yellow-950 font-bold">Resep Kita</span>
                </div>
                
                <span class="font-light font-[fredoka] text-gray-400 mb-8">
                    Selamat datang di ResepKita, Silahkan Daftar untuk melanjutkan !
                </span>
                
                <!-- Menampilkan Pesan Error / Alert di atas Form -->
                <?php echo $pesan; ?>
                
                <div class="py-4">
                    <span class="mb-2 font-[fredoka] text-yellow-950 text-md">Username</span>
                    <input
                        type="text"
                        class="w-full p-2 border border-gray-300 rounded-md placeholder:font-light placeholder:text-gray-500"
                        name="name"
                        id="name" required />
                </div>
                <div class="py-4">
                    <span class="mb-2 font-[fredoka] text-yellow-950 text-md">Password</span>
                    <input
                        type="password"
                        name="pass"
                        id="pass"
                        class="w-full p-2 border border-gray-300 rounded-md placeholder:font-light placeholder:text-gray-500" required />
                </div>
                
                <button type="submit" name="register"
                    class="w-full bg-yellow-950 font-[fredoka] text-white p-2 rounded-lg mb-6 hover:bg-[#997d60] hover:text-white hover:border hover:border-gray-300 transition-all">
                    Daftar Sekarang !
                </button>
                
                <div class="text-center font-[fredoka] text-gray-400">
                    Sudah punya akun?
                    <a href="login.php" class="font-bold text-yellow-950 hover:text-gray-600 transition-colors">Login disini</a>
                </div>
            </form>

            <div class="relative">
                <img src="./assets/iconlogin.jpg" alt="img" class="w-[400px] h-full hidden rounded-r-2xl md:block object-cover" />
                <div class="absolute hidden bottom-10 right-6 p-6 bg-white bg-opacity-30 backdrop-blur-sm rounded drop-shadow-lg md:block">
                    <span class="text-white font-[fredoka] text-xl">"Temukan berbagai cita rasa kuliner <br/> nusantara dari berbagai tempat di <br/> seluruh Indonesia"</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>