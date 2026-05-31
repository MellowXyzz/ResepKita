<?php
session_start();
include 'koneksi.php';

// Jika sudah login, langsung lempar ke index
if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$pesan = "";
if (isset($_POST['login'])) {
    // Mengambil name dari input HTML ('name' dan 'pass')
    $username = $_POST['name'];
    $password = $_POST['pass'];

    // Menggunakan Prepared Statements agar jauh lebih aman dari SQL Injection
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Verifikasi user ditemukan dan password hash sesuai
    if ($row && password_verify($password, $row['PASSWORD'])) {
        // Set session data
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['ROLE'] = $row['ROLE'];

        header("Location: index.php");
        exit();
    } else {
        // Mempersingkat pesan error agar tidak membocorkan informasi spesifik ke penyerang
        $pesan = "<p class='text-red-500 font-[fredoka] mb-4'>Username atau Password salah!</p>";
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
    <title>Login-ResepKita</title>
</head>
<body class=" bg-center bg-repeat bg-[length:300px_auto]" style="background-image: url('assets/navbg.jpg')">

    <div class="flex items-center justify-center min-h-screen shadow-xl " >
        <div class="flex flex-col bg-[#EFE6D5] shadow-2xl rounded-2xl md:flex-row border-2 border-[#c5b598] ">
            <form action="" method="POST" class="flex flex-col justify-center p-8 md:p-14">
                
                <div class="flex items-center gap-3 mb-3">
                    <img src="./assets/logo.png" alt="Logo" class="w-12 h-12 object-contain" />
                    <span class="text-4xl font-[arapey] text-yellow-950 font-bold">Resep Kita</span>
                </div>
                
                <span class="font-light font-[fredoka] text-gray-400 mb-8 text-yellow-950">
                    Selamat datang di ResepKita, Silahkan Login untuk melanjutkan !
                </span>

                <?php echo $pesan; ?>
                
                <div class="py-4">
                    <span class="mb-2 font-[fredoka] text-yellow-950 text-md">Username</span>
                    <input
                        type="text"
                        class="w-full p-2 border border-[#c5b598] bg-[#F5EFE4] rounded-full placeholder:font-light placeholder:text-gray-500"
                        name="name"
                        id="name" required />
                </div>
                <div class="py-4">
                    <span class="mb-2 font-[fredoka] text-yellow-950 text-md">Password</span>
                    <input
                        type="password"
                        name="pass"
                        id="pass"
                        class="w-full p-2 border border-[#c5b598] bg-[#F5EFE4] rounded-full placeholder:font-light placeholder:text-gray-500" required />
                </div>
                
                <button type="submit" name="login"
                    class="w-full bg-yellow-950 font-[fredoka] text-white p-2 rounded-lg mb-6 hover:bg-[#997d60] hover:text-white hover:border hover:border-gray-300 transition-all">
                    Sign in
                </button>
                
                <div class="text-center font-[fredoka] text-yellow-500">
                    Belum punya akun?
                    <a href="register.php" class="font-bold text-yellow-950 hover:text-gray-600 transition-colors">Daftar disini</a>
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